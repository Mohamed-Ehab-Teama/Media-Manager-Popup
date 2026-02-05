<script src="https://cdn.tiny.cloud/1/nberdbnr3d7njhn4mhdygxqzhk97si2q75cqcf86b2qyhxoc/tinymce/8/tinymce.min.js"
    referrerpolicy="origin" crossorigin="anonymous"></script>


<script>
    // start TinyMCE
    tinymce.init({
        selector: 'textarea#myeditorinstance',
        plugins: 'code table lists',
        toolbar: 'undo redo | blocks | bold italic | alignleft aligncenter alignright | indent outdent | bullist numlist | code | table | myCustomButton',

        height: '85vh',
        resize: true,

        setup: function (editor) {
            editor.ui.registry.addButton('myCustomButton', {
                text: 'Media Manager',
                tooltip: 'Open Media Manager Popup',
                onAction: function () {
                    window.activeEditor = editor;
                    openMediaManager();
                }
            });
        },

    });

    // Open Modal
    function openMediaManager() {
        document.getElementById('mediaModal').style.display = 'flex';
    }
    // Close Modal
    function closeMediaManager() {
        document.getElementById('mediaModal').style.display = 'none';
    }

</script>