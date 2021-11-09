const mix = require('laravel-mix')

mix.setPublicPath('public')

mix.postCss("./resources/css/styles.css", "public/css", [
    require("tailwindcss"),
])
.version()

// mix.copyDirectory('public', '../../../public/vendor/uccello/module-manager')
