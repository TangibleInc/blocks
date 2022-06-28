module.exports = {
  build: [

    // Frontend - See includes/enqueue.php

    // {
    //   src: 'assets/src/index.js',
    //   dest: 'assets/build/tangible-blocks.min.js'
    // },
    // {
    //   src: 'assets/src/index.scss',
    //   dest: 'assets/build/tangible-blocks.min.css'
    // },

    // Admin

    {
      src: 'assets/src/admin.scss',
      dest: 'assets/build/admin.min.css'
    },
  ],
  format: [
    'includes',
    'assets/src'
  ]
}