BRMedia Plugin

BRMedia is a powerful WordPress plugin that allows you to manage, style, and deliver music and video content with fully customizable templates, waveforms, visualizers, downloads, and analytics.

Installation
	1.	Upload the brmedia folder to your /wp-content/plugins/ directory.
	2.	Activate the plugin from the WordPress admin under Plugins.
	3.	Access BRMedia from the admin menu.

Features
	•	Custom audio & video templates (7 audio, 4 video)
	•	Popup & fullscreen player controls (not set as default)
	•	Waveform + visualizer (WaveSurfer.js + visualizer.js)
	•	Advanced admin settings for each template
	•	Admin dashboard with activity and stats
	•	Shortcodes for media, covers, downloads, tracklists
	•	Geolocation-based analytics (ip-api.com integration)
	•	Custom widgets for sidebars/footers
	•	Fully styled download blocks with icon settings
	•	Font Awesome icon picker with preview modal
	•	Tracklist & cover shortcode (separate from player)

Shortcodes

[brmedia_audio id="123" template="default"]
[brmedia_video id="456" template="modern"]
[brmedia_tracklist id="123"]
[brmedia_cover id="123"]
[brmedia_download id="123"]

Each shortcode supports additional settings from the ACP.

Folder Structure (Simplified)

brmedia/
├── brmedia.php
├── uninstall.php
├── README.md
├── ajax.php
├── assets/
│   ├── css/
│   ├── js/
│   └── images/
├── includes/
│   ├── admin/
│   ├── common/
│   ├── public/
│   └── templates/

Full file tree is documented separately to avoid markdown copying issues.

Coming Soon
	•	BRMedia Podcasts
	•	BRMedia Radio
	•	BRMedia Chat
	•	BRMedia Gaming

Author

Rhys Cole / Upalnite
https://blackburnravers.co.uk

License

Released under the GPLv2 License.

⸻