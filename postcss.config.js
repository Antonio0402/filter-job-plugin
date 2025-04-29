module.exports = {
  plugins: [
    require('postcss-prefixer')({
      prefix: 'job-filter-',
      ignore: [/^\.is-/, /^\.has-/]
    })
  ],
};