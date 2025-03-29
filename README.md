# BRMedia Plugin

**Advanced Modular Audio & Video Player for WordPress**

---

## Overview

BRMedia is a feature-rich WordPress plugin designed for DJs, radio broadcasters, content creators, and anyone needing a customizable audio/video player. It supports tracklist timestamps, waveform previews, shortcode management, advanced analytics, and modular plugin extensions.

---

## Features

### Media Controls
- Upload MP3/MP4 via WordPress Media Library
- Featured images used as cover art
- Tracklist / timestamp entry (manual or .txt import)
- Toggle download option per media
- Audio preview in post editor

### Tracklist Support
- 00:00:00 timestamp format
- Auto-formatting of line breaks
- Supports manual input or `.txt` file upload

### Dashboard & ACP
- Clean dashboard widgets with quick stats
- Chart.js integration for plays/downloads
- ACP tabs for settings: General, Templates, Sharing, Stats, Footer, Popup
- Shortcode generator
- Template previews in admin
- Color pickers & full CSS variable support

### Shortcodes
Use:
```
[brmedia_player id="123"]
```
Fully customizable with template, controls, and metadata options.

### Modular Addons
- BRMedia Chat
- BRMedia Radio
- BRMedia Podcasts
- BRMedia Gaming
- BRMedia Stats (chart dashboard)
- BRMedia Downloads

Each can be enabled or disabled via the plugin’s settings interface.

---

## File Structure

```
brmedia/
├── admin/
│   ├── class-brmedia-dashboard.php
│   ├── class-brmedia-settings.php
│   ├── class-brmedia-metaboxes.php
│   └── ...
├── addons/
│   ├── chat/
│   ├── radio/
│   ├── downloads/
│   └── ...
├── assets/
│   ├── css/
│   ├── js/
│   └── icons/
├── includes/
│   ├── post-types.php
│   ├── functions.php
│   └── ...
├── templates/
│   ├── email/
│   ├── shortcode-output/
│   ├── previews/
├── uninstall.php
└── brmedia.php
```

---

## Admin Menu Layout

```
BRMedia
├── Dashboard
├── Settings
│
├── Music
│   ├── All Tracks
│   ├── Add New
│   ├── Genres
│   └── Tags
│
├── Video
│   ├── All Videos
│   ├── Add New
│   ├── Categories
│   └── Tags
│
├── Shortcodes
└── Stats
```

---

## Installation

1. Upload the plugin folder to `/wp-content/plugins/brmedia/`
2. Activate via WordPress Admin > Plugins
3. Access all controls under the **BRMedia** menu

---

## Roadmap

- Footer Player (addon, with toggle in ACP)
- Advanced waveform and spectrum visualizer
- Front-end AJAX track filtering
- Track analytics by post, genre, and artist
- REST API endpoints for dynamic front-end apps
- Frontend uploader (for select user roles)

---

## Support

- Site: https://blackburnravers.co.uk  
- GitHub Issues (coming soon)  
- Contact via BRMedia dashboard widget

---

## License

GPLv2 or later  
Open-source. Free to modify, extend, and redistribute.

---

## Author

Developed by Rhys Cole  
Powered by Plyr.js, Chart.js, and WordPress Core APIs.