Nova.booting((Vue, router, store) => {
  router.addRoutes([
    {
      name: 'gutenberg',
      path: '/gutenberg',
      component: require('./components/Tool'),
    },
  ])
})
