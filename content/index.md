## Mosquito : SSG + CMS

**Static Site Generator and Content Management System**.<br />
**mosquito** is a mini-flyweight **Static Site Generator** written in PHP that can also be used as simple CMS, it compiles Markdown files into HTML using the folder structure where the files are stored as URL router.


### Main Features :

- **Markdown Based**. You don't need to know HTML although you can also use it inside your Markdown files.
- **URLs generated using the Folder Structure**. The way you store your markdown content files, the way the site will be generated.
- **No configuration and minimal requirements**. Just get it and start working with it, you'll only need PHP which is already on your computer if you are on a Mac or Linux system.
- **Easy Theming and Templating**. You can create new themes very easily by using the predefined template variables and creating your CSS styles.
- **Multi functional**. You can use mosquito as a Static Site Generator or as CMS. The choice is yours.
- **Flat file**. There's no database to worry about, your data is the one on the content folder. Portable and legible
- **Easy to Extend**. You can easily create new functions / template variables and make them available on your mosquito site.


### Requirements :

If you are on a Unix machine (Mac OS X, Linux) you probably won't need anything as your system may come with PHP already bundled. Anyway, basic needs to make mosquito work is **PHP**, with a version higher or equal to 5.4 to run the built-in server and preview your generated site, and, when using mosquito as CMS, the Apache `mod_rewrite` is required in order to create friendly URLs.
