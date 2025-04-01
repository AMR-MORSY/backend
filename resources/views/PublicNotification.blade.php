<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    {{-- <title>{{ $data['title'] }}</title> --}}
</head>

<body>
    <div class="container mt-20" v-if="notifications.length > 0">
        <div class="header">
            <div class="notification-content">
                {{-- <div class="notification-title">{{ $data['title'] }}</div> --}}
                <div class="notification-message">

                    {{ $data['message'] }}
                    <a href="{{ $data['link'] }}"><strong>.Try it out now! 🎉"</strong></a>
                </div>
                <p>Thank you.</p>
                <p>Cairo Team</p>

            </div>
        </div>
    </div>

</body>

</html>


<style>
    :root {
        --primary-color: #4285f4;
        --secondary-color: #f1f3f4;
        --text-color: #202124;
        --light-text: #5f6368;
        --border-color: #dadce0;
        --unread-bg: #f8f9fa;
    }

    * {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
        font-family: "Segoe UI", Roboto, Oxygen, Ubuntu, Cantarell, "Open Sans",
            "Helvetica Neue", sans-serif;
    }

    body {
        background-color: #f8f9fa;
        color: var(--text-color);
    }

    .container {
        max-width: 800px;
        margin: 20px auto;
        padding: 0 15px;
    }

    .header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 20px;
    }

    h1 {
        font-size: 24px;
        font-weight: 500;
    }


    .notification-content {
        flex-grow: 1;
    }

    .notification-title {
        font-weight: 500;
        margin-bottom: 4px;
    }

    .notification-message {
        color: var(--light-text);
        font-size: 16px;
        margin-bottom: 6px;
        line-height: 1.4;
    }
</style>
