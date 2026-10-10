# playlist-legacy-device

Minimal PHP media portal for legacy and low-end mobile browsers (Nokia 6300, Sony Ericsson K300, Lumia). Drop files into the upload folders and they appear on the pages automatically. No JavaScript, no database, no build step.

## How it works

`index.php` is a menu with three pages: Video, Audio, Games. Each page scans its folder under `uploads/` and renders plain links. The phone's browser hands each link to its native player or installer.

## Structure

```
playlist-legacy-device/
  index.php
  .htaccess
  pages/
    video.php
    audio.php
    games.php
  tools/
    lib.php
  uploads/
    audio/
    video/
    games/
```

## Supported formats

| Folder | Formats | Notes |
|--------|---------|-------|
| `uploads/audio` | `.mp3` | |
| `uploads/video` | `.3gp` | Nokia 6300, K300 |
| `uploads/video` | `.mp4` | Lumia and newer devices |
| `uploads/games` | `.jar` (+ `.jad`) | Java ME games, Nokia 6300, K300 |

Extensions must be lowercase.

## Requirements

- PHP 7+ (shared hosting such as cPanel is enough)
- Plain `http://` access. Nokia 6300 and K300 cannot connect over HTTPS, so disable forced HTTPS redirect on the host.

## Deployment

1. Upload `index.php`, `pages/` and `tools/` to `public_html` (or a subdomain folder).
2. Append the MIME rules below to the existing `.htaccess`. Do not overwrite cPanel-generated blocks.
3. Create `uploads/audio`, `uploads/video` and `uploads/games`.
4. Add media files.
5. Open `http://your-domain` on the phone.

```apache
AddType video/3gpp .3gp
AddType video/mp4 .mp4
AddType audio/mpeg .mp3
AddType application/java-archive .jar
AddType text/vnd.sun.j2me.app-descriptor .jad
```

## Games

- Games must be Java ME (MIDP) `.jar` files. Lumia does not run them.
- If a `.jad` file with the same name exists, the link points to it (more reliable install). Otherwise the link points to the `.jar`.
- `MIDlet-Jar-URL` and `MIDlet-Jar-Size` in the `.jad` must match the real file, or the install fails.
- Match the target screen: Nokia 6300 is 240x320, K300 is 128x160.

## Media conversion (ffmpeg)

Audio:

```bash
ffmpeg -i in.wav -c:a libmp3lame -b:a 96k -ar 44100 out.mp3
```

Video for Nokia 6300 (H.263, 176x144):

```bash
ffmpeg -i in.mp4 -vf scale=176:144 -r 15 -c:v h263 -b:v 150k \
  -c:a aac -ar 16000 -ac 1 -b:a 32k out.3gp
```

Video for Sony Ericsson K300 (smaller, keep under about 1 MB):

```bash
ffmpeg -i in.mp4 -vf scale=128:96 -r 12 -c:v h263 -b:v 80k \
  -c:a aac -ar 8000 -ac 1 -b:a 16k out.3gp
```

Video for Lumia and modern devices (H.264 + AAC):

```bash
ffmpeg -i in.mkv -vf scale=480:-2 -c:v libx264 -profile:v baseline \
  -b:v 500k -c:a aac -b:a 96k out.mp4
```

## Notes

- Use simple file names: `song_1.mp3`, no spaces or non-ASCII characters.
- Keep video short. Legacy devices have very limited storage and memory.
- The pages work without CSS. Styles are inline in `<style>` and limited to basic properties.
- If both `clip.mp4` and `clip.3gp` exist, both are listed with a type label.

## License

Copyright (c) Webluna Software. All rights reserved.