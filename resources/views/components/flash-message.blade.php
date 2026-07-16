@if (session('success') || $errors->any())
    <div class="ml-public-feedback-stack">
        @if (session('success'))
            <div
                class="alert alert-success ml-public-alert ml-public-alert--success"
                role="status"
            >
                <span class="ml-public-alert__icon" aria-hidden="true">
                    <i class="bi bi-check-circle"></i>
                </span>

                <div class="ml-public-alert__content">
                    {{ session('success') }}
                </div>
            </div>
        @endif

        @if ($errors->any())
            <div
                class="alert alert-error ml-public-alert ml-public-alert--error"
                role="alert"
            >
                <span class="ml-public-alert__icon" aria-hidden="true">
                    <i class="bi bi-exclamation-circle"></i>
                </span>

                <div class="ml-public-alert__content">
                    <strong>Data belum valid.</strong>

                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
        @endif
    </div>
@endif
