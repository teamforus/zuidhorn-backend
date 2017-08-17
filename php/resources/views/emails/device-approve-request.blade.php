<div>
    <h4>Hello, {{ $device->shop_keeper->user->full_name }},</h4>
    <p>You received this letter because someone (perhaps you) entered through a device that you did not previously use in the Furus application. If you did, please follow the link below to add this device to the whitelist. Without this you can not use it.</p>
    <p><a href="{{ $device->urlApproveLink() }}">Confirmation link</a></p>
</div>