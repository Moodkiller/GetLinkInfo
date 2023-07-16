### Setup
1. Copy `getlinkinfo-v2.php` to your server or website.
2. Copy `GetLinkInfo.mrc` to your mIRC script directory and load with `/load -a scripts/GetLinkInfo.mrc`.
   * If using your own webserver, change [this line]( https://github.com/Moodkiller/GetLinkInfo/blob/main/GetLinkInfo.mrc#L66) to match.
   * Any changes can be reloaded with the `/reload -a scripts/GetLinkInfo.mrc`.

### Usage:
`getlinkinfo-v2.php?url=ABC`   
Enable with `+/-Linkinfo` when on a channel (and have mod status)    
The mIRC script is set to parse for links automatically and feed the url to the hosted phps script when enabled on a channel. 

### Output (web):
![image](https://github.com/Moodkiller/GetLinkInfo/assets/11341653/4e0d4668-ce75-4cb2-a056-bbc3685682ea)

### Output (irc):
![image](https://github.com/Moodkiller/GetLinkInfo/assets/11341653/df9513d6-a874-403d-88f8-4b683eef828c)

### Features (PHP):
* Toggle for debugging / easy to find where the "title" is located in the pulled HTML.
* Can scrape YouTube titles without an API.
* Output is placed within `<linkinfo>` tags taht can easly parsed by other services. I.e the aforementioend mIRC script.
* Lightweight.
* Only requires PHP.
* Shows PHP errors by default.
* Shows preview of Image if an image is found and outputs relevant data:   
   <img src="https://github.com/Moodkiller/GetLinkInfo/assets/11341653/b5e1267c-62e4-45a3-b166-07accc97b407" width="255px">

### Features (mIRC):
* Can be enabled (or disabled) per channel by issuing `+/-Linkinfo` as an [owner or mod](https://github.com/Moodkiller/GetLinkInfo/blob/main/GetLinkInfo.mrc#L27) of the script.
* Context menu (easily enable or disable silently).
* Ignore user/nick list (edit this [line](https://github.com/Moodkiller/GetLinkInfo/blob/main/GetLinkInfo.mrc#L21)).
* Easy customisable logo and calours for message sent to IRC
  
### Limitations:
* Doesnt work on sites that require cookies and/or Javascript.
* Doesnt work on sites that have more than 20 redirects.
* Depending on your server configureation, `https` connections may not work.
