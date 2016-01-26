<!--
order : 2
-->

### URLs

Using *mosquito* as CMS :

- A file at `content/index.md` will be accessed at `/`.  
- A file at `content/text.md` will be accessed at `/text`.  
- A file at `content/sub/index.md` will be accessed at `/sub/`.  
- A file at `content/sub/text.md` will be accessed at `/sub/text`.  
- If a file does not exist or cannot be found, `content/404.md` will be used in its place.

If you are using *mosquito* as Static Site Generator :

- A file at `content/index.md` will be accessed at `/`.  
- A file at `content/text.md` will be accessed at `/text.html`.  
- A file at `content/sub/index.md` will be accessed at `/sub/`.  
- A file at `content/sub/text.md` will be accessed at `/sub/text.html`.  
- If a file does not exist or cannot be found, should be directed to `/404.html` which is created from the file `content/404.md`.
