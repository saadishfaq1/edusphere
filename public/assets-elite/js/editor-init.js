/* Quill editor init */
(function () {
  function init() {
    var el = document.getElementById('quillEditor');
    if (el && window.Quill) {
      new Quill(el, {
        theme: 'snow',
        modules: {
          toolbar: [
            [{ header: [1, 2, 3, false] }],
            ['bold', 'italic', 'underline', 'strike'],
            [{ color: [] }, { background: [] }],
            [{ list: 'ordered' }, { list: 'bullet' }],
            ['link', 'image', 'blockquote', 'code-block'],
            [{ align: [] }],
            ['clean']
          ]
        }
      });
    }
  }
  if (document.readyState === 'loading') document.addEventListener('DOMContentLoaded', init);
  else init();
})();
