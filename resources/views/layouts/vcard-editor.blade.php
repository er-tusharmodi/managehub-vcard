<!doctype html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>@yield('title', 'vCard Editor')</title>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
        <link href="https://cdn.jsdelivr.net/npm/@mdi/font@7.4.47/css/materialdesignicons.min.css" rel="stylesheet">
        @livewireStyles
    </head>
    <body class="bg-light">
        <div class="container py-4">
            {{ $slot }}
        </div>
        
        <div class="position-fixed bottom-0 end-0 p-3" style="z-index: 1090; max-width: calc(100vw - 24px);">
            <div id="vcard-toast-container" class="toast-container"></div>
        </div>
        
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
        @livewireScripts
        
        <script>
            var toastListenerRegistered = false;

            function showToast(type, message) {
                var container = document.getElementById('vcard-toast-container');
                if (!container || !message) return;

                var bgColor = type === 'success' ? 'text-bg-success' :
                             type === 'error' ? 'text-bg-danger' :
                             type === 'warning' ? 'text-bg-warning' : 'text-bg-info';

                var toastEl = document.createElement('div');
                toastEl.className = 'toast align-items-center ' + bgColor + ' border-0 mb-2';
                toastEl.setAttribute('role', 'alert');
                toastEl.setAttribute('aria-live', 'assertive');
                toastEl.setAttribute('aria-atomic', 'true');
                toastEl.innerHTML = '<div class="d-flex"><div class="toast-body">' + message + '</div><button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button></div>';

                container.appendChild(toastEl);

                if (window.bootstrap && typeof bootstrap.Toast === 'function') {
                    var toast = new bootstrap.Toast(toastEl);
                    toast.show();
                    toastEl.addEventListener('hidden.bs.toast', function () {
                        if (toastEl && toastEl.parentNode) {
                            toastEl.remove();
                        }
                    });
                } else {
                    toastEl.classList.add('show');
                    setTimeout(function () {
                        if (toastEl && toastEl.parentNode) {
                            toastEl.remove();
                        }
                    }, 5000);
                }
            }

            function showConfirmToast(message, onConfirm) {
                var container = document.getElementById('vcard-toast-container');
                if (!container || !message) return;

                var toastEl = document.createElement('div');
                toastEl.className = 'toast align-items-center text-bg-warning border-0 mb-2 fade';
                toastEl.setAttribute('role', 'alert');
                toastEl.setAttribute('aria-live', 'assertive');
                toastEl.setAttribute('aria-atomic', 'true');
                toastEl.innerHTML = '' +
                    '<div class="d-flex">' +
                        '<div class="toast-body">' + message + '</div>' +
                        '<div class="d-flex align-items-center gap-2 pe-2">' +
                            '<button type="button" class="btn btn-sm btn-danger text-white" data-confirm="true" style="background-color: #dc3545; border-color: #dc3545;">Delete</button>' +
                            '<button type="button" class="btn btn-sm btn-light" data-cancel="true">Cancel</button>' +
                        '</div>' +
                    '</div>';

                container.appendChild(toastEl);

                var confirmBtn = toastEl.querySelector('[data-confirm]');
                var cancelBtn = toastEl.querySelector('[data-cancel]');

                function cleanup() {
                    if (toastEl && toastEl.parentNode) {
                        toastEl.remove();
                    }
                }

                if (confirmBtn) {
                    confirmBtn.addEventListener('click', function () {
                        cleanup();
                        if (typeof onConfirm === 'function') {
                            onConfirm();
                        }
                    });
                }

                if (cancelBtn) {
                    cancelBtn.addEventListener('click', function () {
                        cleanup();
                    });
                }

                if (window.bootstrap && typeof bootstrap.Toast === 'function') {
                    var toast = new bootstrap.Toast(toastEl, { autohide: false });
                    toast.show();
                } else {
                    toastEl.classList.add('show');
                }
            }

            document.addEventListener('DOMContentLoaded', function () {
                var successMessage = @json(session('success'));
                var errorMessage = @json(session('error'));
                if (successMessage) {
                    showToast('success', successMessage);
                }
                if (errorMessage) {
                    showToast('error', errorMessage);
                }
            });

            function normalizePayload(args) {
                if (!args || !args.length) return null;

                var payload = args[0];
                if (Array.isArray(payload)) {
                    if (payload.length === 1 && payload[0] && payload[0].message) {
                        return { type: payload[0].type || 'info', message: payload[0].message };
                    }
                    if (payload.length >= 2 && typeof payload[1] === 'string') {
                        return { type: payload[0] || 'info', message: payload[1] };
                    }
                }
                if (typeof payload === 'string') {
                    return { type: 'info', message: payload };
                }

                if (payload && payload.message) {
                    return { type: payload.type || 'info', message: payload.message };
                }

                if (args.length >= 2 && typeof args[1] === 'string') {
                    return { type: payload || 'info', message: args[1] };
                }

                if (payload && payload.detail && payload.detail.message) {
                    return { type: payload.detail.type || 'info', message: payload.detail.message };
                }

                return null;
            }

            function handleNotifyEvent(event) {
                var normalized = normalizePayload([event.detail]);
                if (!normalized) {
                    normalized = normalizePayload([event]);
                }
                if (normalized) {
                    showToast(normalized.type, normalized.message);
                }
            }

            document.addEventListener('notify', handleNotifyEvent);
            window.addEventListener('notify', handleNotifyEvent);

            function handleConfirmDeleteEvent(event) {
                var detail = (event && event.detail) ? event.detail : {};
                var componentId = detail.id;
                var index = detail.index;
                var path = detail.path || '';
                var message = detail.message || 'Delete this item?';
                var method = detail.method || 'deleteVcardConfirmed';
                if (!componentId || typeof index === 'undefined') {
                    return;
                }

                showConfirmToast(message, function () {
                    if (window.Livewire && Livewire.find) {
                        var component = Livewire.find(componentId);
                        if (component) {
                            if (path !== '') {
                                component.call(method, index, path);
                            } else {
                                component.call(method, index);
                            }
                        }
                    }
                });
            }

            document.addEventListener('confirm-delete', handleConfirmDeleteEvent);

            function registerLivewireListener() {
                if (toastListenerRegistered) return;
                if (window.Livewire && Livewire.on) {
                    toastListenerRegistered = true;
                    Livewire.on('notify', function () {
                        var normalized = normalizePayload(arguments);
                        if (normalized) {
                            showToast(normalized.type, normalized.message);
                        }
                    });
                }
            }

            document.addEventListener('livewire:init', function () {
                registerLivewireListener();
            });
            document.addEventListener('livewire:initialized', function () {
                registerLivewireListener();
            });

            if (window.Livewire) {
                registerLivewireListener();
            }

            // Close modal event listener
            document.addEventListener('close-modal', function() {
                var openModals = document.querySelectorAll('.modal.show');
                openModals.forEach(function(modal) {
                    var modalInstance = bootstrap.Modal.getInstance(modal);
                    if (modalInstance) {
                        modalInstance.hide();
                    }
                });
            });
        </script>
    </body>
</html>
