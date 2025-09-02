<!-- Bootstrap core JavaScript-->
<script src="<?= base_url('vendor/jquery/jquery.min.js') ?>"></script>
<script src="<?= base_url('vendor/bootstrap/js/bootstrap.bundle.min.js') ?>"></script>

<!-- Core plugin JavaScript-->
<script src="<?= base_url('vendor/jquery-easing/jquery.easing.min.js') ?>"></script>

<!-- Custom scripts for all pages-->
<script src="<?= base_url('js/sb-admin-2.min.js') ?>"></script>
<!-- jQuery -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>


<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css" xintegrity="sha384-xOolHFLEh07PJGoPkLv1IbcEPTNtaed2xpHsD9ESMhHgDeHrPkgyolf/cR7Yk+w" crossorigin="anonymous">
<!-- Font Awesome untuk ikon -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" xintegrity="sha512-1ycn6IcaQQ40/MKBW2W4Rhis/DbILU74C1vSrLJxCq57o941Ym01SwNsOMqvEBFlcgUa6xLiPY/NS5R+E6ztJQ==" crossorigin="anonymous" referrerpolicy="no-referrer" />
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/lightbox2/2.11.3/css/lightbox.min.css">
<script src="https://cdnjs.cloudflare.com/ajax/libs/lightbox2/2.11.3/js/lightbox.min.js"></script>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<!-- Bootstrap 4 CSS -->
<link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet" />

<!-- laporan -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        let hasAnimated = false;

        // Counter Animation
        function animateCounter(element, target, duration = 2000) {
            let start = 0;
            let startTime = null;

            function animation(currentTime) {
                if (startTime === null) startTime = currentTime;
                let timeElapsed = currentTime - startTime;
                let progress = Math.min(timeElapsed / duration, 1);

                let easedProgress = 1 - Math.pow(1 - progress, 4);
                let current = Math.floor(easedProgress * target);

                element.textContent = current;

                if (progress < 1) {
                    requestAnimationFrame(animation);
                }
            }

            requestAnimationFrame(animation);
        }

        // Progress Bar Animation
        function animateProgressBar(element, percentage, delay = 0) {
            setTimeout(() => {
                element.style.width = percentage + '%';
            }, delay);
        }

        // Initialize Animations - ONE TIME ONLY
        function initializeAnimations() {
            if (hasAnimated) return;

            const statsCards = document.querySelectorAll('.modern-stats-card');
            statsCards.forEach((card, index) => {
                setTimeout(() => {
                    card.classList.add('animate-in');
                }, index * 150);
            });

            const counters = document.querySelectorAll('.counter');
            counters.forEach((counter, index) => {
                const target = parseInt(counter.getAttribute('data-target'));
                setTimeout(() => {
                    animateCounter(counter, target, 2500);
                }, index * 200 + 800);
            });

            const progressBars = document.querySelectorAll('.indicator-bar');
            progressBars.forEach((bar, index) => {
                const percentage = parseInt(bar.getAttribute('data-percentage'));
                animateProgressBar(bar, percentage, index * 300 + 1200);
            });

            hasAnimated = true;
        }

        // Enhanced hover and click effects
        const statsCards = document.querySelectorAll('.modern-stats-card');

        statsCards.forEach(card => {
            // Click ripple effect
            card.addEventListener('click', function(e) {
                const ripple = document.createElement('div');
                const rect = this.getBoundingClientRect();
                const size = Math.max(rect.width, rect.height);
                const x = e.clientX - rect.left - size / 2;
                const y = e.clientY - rect.top - size / 2;

                ripple.style.cssText = `
                        position: absolute;
                        width: ${size}px;
                        height: ${size}px;
                        left: ${x}px;
                        top: ${y}px;
                        background: rgba(255, 255, 255, 0.4);
                        border-radius: 50%;
                        transform: scale(0);
                        animation: ripple 0.6s linear;
                        pointer-events: none;
                        z-index: 1;
                    `;

                this.appendChild(ripple);

                setTimeout(() => {
                    if (ripple.parentNode) {
                        ripple.parentNode.removeChild(ripple);
                    }
                }, 600);

                this.style.transform = 'scale(0.98)';
                setTimeout(() => {
                    this.style.transform = '';
                }, 150);
            });
        });

        // Initialize with intersection observer
        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting && !hasAnimated) {
                    initializeAnimations();
                    observer.disconnect();
                }
            });
        }, {
            threshold: 0.2,
            rootMargin: '0px 0px -50px 0px'
        });

        const statsContainer = document.querySelector('.row.g-4.mb-5');
        if (statsContainer) {
            observer.observe(statsContainer);
        }

        // Fallback initialization
        setTimeout(() => {
            if (!hasAnimated) {
                initializeAnimations();
            }
        }, 50);
    });
</script>

<!-- Bootstrap 4 JS + Popper.js -->
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

<?php if (session()->getFlashdata('success')): ?>
    <script>
        Swal.fire({
            icon: 'success',
            title: 'Berhasil',
            text: '<?= session()->getFlashdata('success') ?>',
            showConfirmButton: false,
            timer: 2000
        });
    </script>
<?php endif; ?>

<?php if (session()->getFlashdata('error')): ?>
    <script>
        Swal.fire({
            icon: 'error',
            title: 'Gagal',
            text: '<?= session()->getFlashdata('error') ?>',
            showConfirmButton: true
        });
    </script>
<?php endif; ?>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const labels = <?= $labels ?? '[]' ?>;
        const dataMasuk = <?= $dataMasuk ?? '[]' ?>;
        const dataKeluar = <?= $dataKeluar ?? '[]' ?>;
        const jenisLabels = <?= $jenisLabels ?? '[]' ?>;
        const jenisTotals = <?= $jenisTotals ?? '[]' ?>;

        // Line Chart - Kopi Masuk & Keluar
        if (document.getElementById('kopiChart')) {
            const ctx = document.getElementById('kopiChart').getContext('2d');
            const chartKopi = new Chart(ctx, {
                type: 'line',
                data: {
                    labels: labels,
                    datasets: [{
                        label: 'Kopi Masuk',
                        data: dataMasuk,
                        borderColor: '#4bc0c0',
                        backgroundColor: 'rgba(75, 192, 192, 0.1)',
                        borderWidth: 2,
                        fill: true,
                        tension: 0.3,
                        pointBackgroundColor: '#4bc0c0',
                    }, {
                        label: 'Kopi Keluar',
                        data: dataKeluar,
                        borderColor: '#ff6384',
                        backgroundColor: 'rgba(255, 99, 132, 0.1)',
                        borderWidth: 2,
                        fill: true,
                        tension: 0.3,
                        pointBackgroundColor: '#ff6384',
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            position: 'bottom'
                        },
                        tooltip: {
                            mode: 'index',
                            intersect: false
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            title: {
                                display: true,
                                text: 'Jumlah (Kg)'
                            }
                        }
                    }
                }
            });
        }

        // Doughnut Chart - Jenis Kopi
        if (document.getElementById('jenisKopiChart')) {
            const ctxJenis = document.getElementById('jenisKopiChart').getContext('2d');
            const chartJenis = new Chart(ctxJenis, {
                type: 'doughnut',
                data: {
                    labels: jenisLabels,
                    datasets: [{
                        data: jenisTotals,
                        backgroundColor: ['#4bc0c0', '#ff6384', '#ffcd56', '#36a2eb', '#9966ff'],
                        borderWidth: 2,
                        borderColor: '#fff'
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    cutout: '70%',
                    plugins: {
                        legend: {
                            position: 'bottom'
                        },
                        tooltip: {
                            callbacks: {
                                label: function(context) {
                                    let label = context.label || '';
                                    let value = context.raw || 0;
                                    return label + ': ' + value.toLocaleString('id-ID') + ' Kg';
                                }
                            }
                        }
                    }
                }
            });
        }
    });
</script>