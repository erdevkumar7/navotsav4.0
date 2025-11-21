<?php

namespace App\Jobs;

use App\Models\Notification;
use App\Models\UserDevice;
use App\Services\FirebaseNotificationService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;

class FBNotificationJob implements ShouldQueue
{
    use Queueable;

    public int $timeout = 120; // Prevents hanging jobs
    public int $tries = 3;     // Retry on failure

    protected string $title;
    protected string $body;
    protected array $data;
    protected array $userIds;

    /**
     * Create a new job instance.
     */
    public function __construct(string $title, string $body, array $userIds = [], array $data = [])
    {
        $this->title   = $title;
        $this->body    = $body;
        $this->data    = $data;
        $this->userIds = array_filter($userIds);
    }

    /**
     * Execute the job.
     */
    public function handle(FirebaseNotificationService $firebaseService): void
    {
        if (empty($this->userIds)) {
            Log::warning('FBNotificationJob skipped: empty userIds.');
            return;
        }

        // Bulk insert notifications for better DB performance
        $notifications = collect($this->userIds)->map(fn($id) => [
            'user_id' => $id,
            'title'   => $this->title,
            'body'    => $this->body,
            'icon'    => 'notify.png',
            'created_at' => now(),
            'updated_at' => now(),
        ])->toArray();

        Notification::insert($notifications);

        // Chunk large queries to avoid memory issues
        UserDevice::whereIn('user_id', $this->userIds)
            ->select('device_token')
            ->whereNotNull('device_token')
            ->chunk(50, function (Collection $tokens) use ($firebaseService) {
                try {
                    $tokens = $tokens->pluck('device_token')->all();
                    if (!empty($tokens)) {
                        $firebaseService->sendToMultipleDevices(
                            $tokens,
                            $this->title,
                            $this->body,
                            $this->data
                        );
                    }
                } catch (\Throwable $e) {
                    Log::error('Firebase send failed in SendWinnerNotificationJob', [
                        'error' => $e->getMessage(),
                        'trace' => $e->getTraceAsString(),
                    ]);
                }
            });
    }
}
