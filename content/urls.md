<!--
order : 2
-->

## URLs

The URL's on *mosquito* are generated using the existing folder structure when storing the documents. So the way you store your markdown content files, the way the site URLs will be generated.

#### Using mosquito as CMS :

- A file at `content/index.md` will be reached at `/`.  
- A file at `content/test.md` will be reached at either `/test` or `/test/`.  
- A file at `content/subfolder/index.md` will be reached at both `/subfolder` and `/subfolder/`.  
- A file at `content/subfolder/test.md` will be reached at `/subfolder/test` and `/subfolder/test/`.  
- If a file does not exist or cannot be found, `content/404.md` will be used in its place.

#### If you are using mosquito as Static Site Generator :

- A file at `content/index.md` will be reached at `/`.  
- A file at `content/test.md` will be reached at `/test.html`.  
- A file at `content/subfolder/index.md` will be reached at `/subfolder/`.  
- A file at `content/subfolder/test.md` will be reached at `/subfolder/test.html`.  
- If a file does not exist or cannot be found, should be directed to `/404.html` which is created from the file `content/404.md`.
