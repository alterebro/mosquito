### Deploy /dist to gh-pages

1. Generate Site

```sh
php -f index.php build
```

2. Commit all on master

```sh
git add .
git commit -m "update"
git push origin master
```

3. Deploy dist/ to gh-pages

```sh
git subtree push --prefix dist origin gh-pages
```