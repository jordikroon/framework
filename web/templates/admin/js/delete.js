window.addEvent('domready',function() {
  $$('a.delete').each(function(el) {
    el.addEvent('click',function(e) {
      e.stop();
      var parent = el.getParent('div');
      var request = new Request({
        url: '',
        link: 'chain',
        method: 'get',
        data: {
          'delete': parent.get('id').replace('record-',''),
          ajax: 1
        },
        onRequest: function() {
          new Fx.Tween(parent,{
            duration:300
          }).start('background-color', '#fb6c6c');
        },
        onSuccess: function() {
          new Fx.Slide(parent,{
            duration:300,
            onComplete: function() {
              parent.dispose();
            }
          }).slideOut();
        }
      }).send();
    });
  });
});