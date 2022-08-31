<script type="text/javascript">
  document.addEventListener('alpine:init', () => {
      // Configs
      Alpine.store('config', {
        headers: {
          'Content-Type': 'application/json',
          'X-CSRF-TOKEN': document.head.querySelector('meta[name=csrf-token]').content
        },
      })

      // Helpers
      Alpine.store('helper', {
        defaultImage: `{{ url('dist/notus-js/img/team-1-800x800.jpg') }}`,
        defaultBackgroundImage: `https://images.unsplash.com/photo-1499336315816-097655dcfbda?ixlib=rb-1.2.1&amp;ixid=eyJhcHBfaWQiOjEyMDd9&amp;auto=format&amp;fit=crop&amp;w=2710&amp;q=80`,
        async getAvatarThumb() {
          return fetch('/fetch/master?mode=get-avatar-thumb')
            .then((response) => {
              if (!response.ok) {
                return false;
              }
              return response.json();
            })
            .then((response) => {
              if (!response.data) {
                return false;
              }
              response = response.data;
              return response.url ?? '';
            })
        },
        async getBackground() {
          return fetch('/fetch/master?mode=get-background')
            .then((response) => {
              if (!response.ok) {
                return false;
              }
              return response.json();
            })
            .then((response) => {
              if (!response.data) {
                return false;
              }
              response = response.data;
              return response.url ?? '';
            })
        },
        async toggleNavbar(collapseID) {
            document.getElementById(collapseID).classList.toggle("hidden");
            document.getElementById(collapseID).classList.toggle("bg-white");
            document.getElementById(collapseID).classList.toggle("m-2");
            document.getElementById(collapseID).classList.toggle("py-3");
            document.getElementById(collapseID).classList.toggle("px-6");
        },
        async openDropdown(event, dropdownId) {
            let element = event.target;
            while (element.nodeName !== 'A') {
                element = element.parentNode;
            }
            Popper.createPopper(element, document.getElementById(dropdownId), {
                placement: 'bottom-start',
            });
            document.getElementById(dropdownId).classList.toggle('hidden');
            document.getElementById(dropdownId).classList.toggle('block');
        },
        async setAlert(type, message) {
          iziToast.settings({
              position: 'topRight',
              transitionIn: 'fadeInDown',
              transitionOut: 'flipOutX',
              balloon: true,
              timeout: 4000
          });
          switch (type) {
              case 'success':
                  iziToast.success({
                      title: 'Success',
                      message: message,
                  });
                  break;
              case 'warning':
                  iziToast.warning({
                      title: 'Warning',
                      message: message,
                  });
                  break;
              case 'danger':
                  iziToast.error({
                      title: 'Error',
                      message: message,
                  });
                  break;
              case 'info':
                  iziToast.info({
                      title: 'Info',
                      message: message,
                  });
                  break;
              default:
                  iziToast.show({
                      message: message,
                  });
                  break;
          }
        },
      })

      // Actions
      Alpine.store('action', {
        async deleteAction ($url) {
          return fetch($url, {
              method: 'DELETE',
              headers: Alpine.store('config').headers,
            }).then((response) => {
              if (response.ok) {
                Swal.fire(
                  'Deleted!',
                  'Your file has been deleted.',
                  'success'
                )
              } else {
                console.error('Error delete data!', response.statusText);
                Swal.fire(
                  'Failed!',
                  'Failed to delete data.',
                  'error'
                )
              }
              return response.ok;
            });
        },
        async deleteConfirm($id, $url, $title = '', $text = '', $icon = '') {
          return Swal.fire({
            title: $title ? $title : 'Delete!',
            text: $text ? $text : 'Are you sure want to delete this data?',
            icon: $icon ? $icon : 'error',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, delete it!'
          }).then((result) => {
            if (!result.isConfirmed) {
              return false;
            }
            return this.deleteAction($url);
          });
        }
      })
    })
</script>