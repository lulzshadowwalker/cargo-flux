<div id="toaster" class="toast toast-end">
</div>

<script>
const $toaster = document.getElementById('toaster');

document.addEventListener('success', (e) => {
    const alert = document.createElement('div');
    alert.classList.add('alert', 'alert-success');
    alert.innerHTML = `<span>${e.detail[0].message}</span>`;
    $toaster.appendChild(alert);
    setTimeout(() => {
        alert.remove();
    }, 5000);
});

document.addEventListener('error', (e) => {
    const alert = document.createElement('div');
    alert.classList.add('alert', 'alert-error');
    alert.innerHTML = `<span>${e.detail[0].message}</span>`;
    $toaster.appendChild(alert);
    setTimeout(() => {
        alert.remove();
    }, 5000);
});

document.addEventListener('info', (e) => {
    const alert = document.createElement('div');
    alert.classList.add('alert', 'alert-info');
    alert.innerHTML = `<span>${e.detail[0].message}</span>`;
    $toaster.appendChild(alert);
    setTimeout(() => {
        alert.remove();
    }, 5000);
});

document.addEventListener('warning', (e) => {
    const alert = document.createElement('div');
    alert.classList.add('alert', 'alert-warning');
    alert.innerHTML = `<span>${e.detail[0].message}</span>`;
    $toaster.appendChild(alert);
    setTimeout(() => {
        alert.remove();
    }, 5000);
});
</script>
