@component('mail::message')
# 🎉 Welcome to {{ config('app.name') }}, {{ $user->name }}!

We’re excited to have you on board. Your account has been created successfully.
Here are your login details:

@component('mail::panel')
- 📧 Email: {{ $user->email }}
- 🔑 Password: {{ $password }}
@endcomponent

@component('mail::button', ['url' => $webUrl, 'color' => 'success'])
🚀 Login to Your Account
@endcomponent

🔒 For your security, please change your password after your first login.

---

If you need any help, feel free to reach out to our support team anytime.
We’re always happy to assist you. 😊

Thanks for joining us,
**The {{ config('app.name') }} Team**
@endcomponent
