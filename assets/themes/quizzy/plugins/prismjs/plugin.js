require.config({
  paths: {
    prismjs: "assets/plugins/prismjs/js/prism.pack",
  },
  shim: {
    prism: {
      exports: "Prism",
    },
  },
});

require(["prismjs", "jquery"], function (prismjs, $) {
  $(document).ready(function () {
    //
  });
});
