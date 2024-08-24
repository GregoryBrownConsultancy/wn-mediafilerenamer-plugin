# Media Files Renamer

This plugin addresses an issue that has long bothered me.

When your user uploads a file to the media manager, the filename remains as is, assuming that the user will be aware of best practices when naming files.

The plugin sanitizes the name of the file before saving it in the Media Manager.

## Motivation

I develop sites for several types of users based on Winter. Most of my users are fairly well versed in the digital world, but, I have recently seen a large number of clients who are completely unaware of certain *best practices* when it comes to naming files.

This has been cause of several minor issues in some of my older projects, as clients upload files with spaces, upper and lower case files and the works. This inevitably leads to issues in some browsers and expected system behaviour.

As a way to mitigate this, I started implementing this functionality inside my projects, and as it became more commonly used, I decided to extract it as a plugin, and this is how this project was born.

## What to expect

When a user uploads a file like: `My picture with Sally.jpg` this plugin will basically sanitze the name to `my-picture-with-sally.jpg` making the file more accessible to browsers, and as a added bonus, more SEO oriented.

The plugin also has a smart feature which is, if the sanitized file already exists, it will append a `--N` to it, where `N` is a number index.

For example:
If the user uploads the following files, sequentially:

1. `My picture with Sally.jpg`
2. `My Picture With Sally.jpg`
3. `my picture with sally.jpg`

which in a *nix based system would be three distinct files, the plugin will convert the first file to:

1. `my-picture-with-sally.jpg`

When it tries to convert the second file, since the slugged name already exists, it will append a `--1` to it:

2. `my-picture-with-sally--1.jpg`

Finally, when trying to save the third file, it will also slug it to
`my-picture-with-sally.jpg`, which already exists, append a `--1` and try again, which also already exists, and finally settle on

3. `my-picture-with-sally--2.jpg`


## CAVEATS AND WARNINGS

### This plugin works only in the media manager

This plugin is designed to work with the media manager only. If you are uploading files directly to the system, this plugin will not fix your problem.

### This plugin works only with the UPLOAD media event

This plugin treats only uploads. Already existing files will not be renamed and renaming a file will not trigger this plugin check. (I might in the future, implement such a feature, but for now, this is well within my needs).

### This plugin works only on files

If a user creates a folder that is not **web friendly**, this plugin will not correct the folder name. It will only correct the media file.

Again, I might in the future implement such a feature, but I have not needed such a thing.

