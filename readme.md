### Setup
1. Copy `getlinkinfo-v2.php` to your server or website.
2. Copy `GetLinkInfo.mrc` to your mIRC script directory and load with `/load -a scripts/GetLinkInfo.mrc`.
   * If using your own webserver, change this line to match: https://github.com/Moodkiller/GetLinkInfo/blob/main/GetLinkInfo.mrc#L66
   * Any changes can be reloaded with the `/reload -a scripts/GetLinkInfo.mrc`.

### Usage:
`getlinkinfo-v2.php?url=ABC`   
Enable with `+/-Linkinfo` when on a channel (and have mod status)    
The mIRC script is set to parse for links automatically and feed the url to the hosted phps script when enabled on a channel. 

### Output (web):
<img width="1043" alt="image" src="https://github.com/Moodkiller/GetLinkInfo/assets/11341653/e4b7fb83-e75a-4dde-ac35-63e0dbec286d">

### Output (irc):
<img width="954" alt="image" src="https://github.com/Moodkiller/GetLinkInfo/assets/11341653/0574d2aa-64f8-48df-8928-bb21fed62843">

### Features (PHP):
* Toggle for debugging / easy to find where the "title" is located in the pulled HTML.
* Can scrape YouTube titles without an API.
* Output is placed within `<linkinfo>` tags taht can easly parsed by other services. I.e the aforementioend mIRC script.
* Lightweight.
* Only requires PHP.
* Shows PHP errors by default.
* Shows preview of Image if an image is found and outputs relevant data:   
   <img width="323" alt="image" src="https://github.com/Moodkiller/GetLinkInfo/assets/11341653/fb504d3c-e9a5-4505-9118-a064d4d1bee3">

### Features (mIRC):
* Can be enabled (or disabled) per channel by issuing `+/-Linkinfo` as an [owner or mod](https://github.com/Moodkiller/GetLinkInfo/blob/main/GetLinkInfo.mrc#L27) of the script.
* Context menu (easily enable or disable silently).
* Ignore user/nick list (edit this [line](https://github.com/Moodkiller/GetLinkInfo/blob/main/GetLinkInfo.mrc#L21)).
* Easy customisable logo and calours for message sent to IRC
  
### Limitations:
* Doesnt work on sites that require cookies and/or Javascript.
* Doesnt work on sites that have more than 20 redirects.
