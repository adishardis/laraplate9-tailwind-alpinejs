var helper = {};
$(document).ready(function () {
    helper = {
        init: function () {
            this.initElement();
        },
        initElement: function () {
            if ($(".notif-alert").length) {
                var alertMessage = $(".notif-alert").val();
                var alertType = $(".notif-alert").data('type');
                helper.setAlert(alertMessage, alertType);
            }
        },
        setAlert: function (message, type) {
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
    };
    helper.init();
});
