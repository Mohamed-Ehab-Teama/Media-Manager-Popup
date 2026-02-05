<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <link rel="icon" href="https://www.egypttoursportal.com/images/2020/07/Egypt-Tours-Portal-Logo.png">

    <title> Media Manager Popup </title>

    <!-- Insert the blade containing the TinyMCE configuration and source script -->
    <x-head.tinymce-config />

    {{-- Custom Styles --}}
    <style>
        body {
            height: 100vh;
        }

        .tox-toolbar__group button[data-mce-name="mycustombutton"] {
            background-color: rgba(255, 166, 0, 0.478);
            color: black;
            transition: all 0.5s;
        }

        .tox-toolbar__group button[data-mce-name="mycustombutton"]:hover {
            background-color: orange;
            color: black;
        }
    </style>

    {{-- Media Manager Modal CSS --}}
    <link rel="stylesheet" href="{{ asset('assets/css/media-manager-modal.css') }}">

</head>

<body>
    <h1> Media Manager Popup </h1>

    <!-- Insert the blade containing the TinyMCE placeholder HTML element -->
    <x-forms.tinymce-editor />



    {{-- Include Media Manager Modal --}}
    @include('media.media-manager-modal')



    {{-- Media Manager Modal JS --}}
    <script src="{{ asset('assets/js/media-manager-modal.js') }}"></script>

</body>

</html>