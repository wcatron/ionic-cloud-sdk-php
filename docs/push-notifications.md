# Push Notifications

## Quick Start Guide

Setup your client.

```
$client = new PushClient(
            [
                'api_token' => "<API_TOKEN>",
                'app_id'    => "<APP_ID>",
                'profile_tag' => "<PROFILE_TAG>"
            ]);
```

Define your targets.

```
$targets = new Targets();
foreach ($users as $user) {
    $targets->addExternalID($user->id);
}

// or
$targets->addTokens(["TOKENA", "TOKENB"]);
```

Create your notifications.

```
$notification = new Notification();
$notification->title = "My Title";
$notification->message = "Message for user...";

// Define device specific configurations.
$notification->ios->title = "iOS Only Total";

// Make use of chaining methods.
$notification->android->title("Android Only Title")->sound("android_beep.tiff")->message("Android only message...");
```

Push your notifications.

```
$client->push($notification, $targets);
```
