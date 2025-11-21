<?php

namespace App\Jobs;

use App\Models\Event;
use App\Models\Notification;
use App\Models\UserDevice;
use App\Services\FirebaseNotificationService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;

class SendWinnerNotificationJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

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
     * 
     * Execute the job.
     */
    public function handle(FirebaseNotificationService $firebaseService): void
    {
        if (empty($this->userIds)) {
            Log::warning('SendWinnerNotificationJob skipped: empty userIds.');
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
            ->chunk(500, function (Collection $tokens) use ($firebaseService) {
                try {
                    $firebaseService->sendToMultipleDevices(
                        $tokens->pluck('device_token')->all(),
                        $this->title,
                        $this->body,
                        $this->data
                    );
                } catch (\Throwable $e) {
                    Log::error('Firebase send failed in SendWinnerNotificationJob', [
                        'error' => $e->getMessage(),
                        'trace' => $e->getTraceAsString(),
                    ]);
                }
            });

        if (isset($data['winner_id']) && !empty($data['winner_id'])) {
            $eventName = Event::find($data['event_id'])->title;
            $title = "🏆 You’re a Winner of {$eventName}!";
            $body = "Congratulations! You’ve won in {$eventName}! Check your account or email for prize details and claim instructions.";
            Notification::create([
                'user_id' => $data['winner_id'],
                'title'   => $title,
                'body'    => $body,
                'icon'    => 'badge.png',
            ]);
            $deviceToken = UserDevice::whereIn('user_id', $data['winner_id'])->first()->device_token;
            $firebaseService->sendToMultipleDevices(
                [$deviceToken],
                $title,
                $body,
                $this->data
            );
        }
    }
}
