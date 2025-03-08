<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, shrink-to-fit=no">
    <meta name="robots" content="noindex, nofollow">
    <title>Admin | Social Engineer Insurance</title>
    <link rel="icon" type="image/x-icon" href="{{ asset('asset/admin/images/favicon.ico') }}"/>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link href="{{ asset('asset/admin/css/bootstrap/bootstrap.min.css') }}" rel="stylesheet" type="text/css" />
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .auth-container {
            width: 100%;
            max-width: 400px;
            padding: 20px;
        }
        .card {
            border-radius: 20px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
            overflow: hidden;
            border: none;
            background-color: rgba(255, 255, 255, 0.9);
            backdrop-filter: blur(10px);
        }
        .card-body {
            padding: 3rem;
        }
        .logo {
            max-width: 150px;
            margin-bottom: 2rem;
            animation: fadeInDown 1s ease-out;
        }
        .welcome-text {
            font-size: 1.1rem;
            color: #6c757d;
            margin-bottom: 2rem;
            animation: fadeIn 1s ease-out 0.5s both;
        }
        .form-control {
            border-radius: 10px;
            padding: 0.75rem 1rem;
            border: 2px solid #e0e6ed;
            background-color: rgba(255, 255, 255, 0.8);
        }
        .form-control:focus {
            border-color: #4361ee;
            box-shadow: 0 0 0 0.2rem rgba(67, 97, 238, 0.25);
        }
        .btn {
            border-radius: 10px;
            padding: 0.75rem 1.5rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            transition: all 0.3s ease;
        }
        .btn-primary {
            background-color: #4361ee;
            border-color: #4361ee;
        }
        .btn-primary:hover {
            background-color: #3a4fd7;
            border-color: #3a4fd7;
            transform: translateY(-2px);
        }
        .timer {
            font-size: 0.9rem;
            color: #6c757d;
        }
        .fade-in {
            animation: fadeIn 0.5s;
        }
        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }
        @keyframes fadeInDown {
            from {
                opacity: 0;
                transform: translateY(-20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        .shake {
            animation: shake 0.82s cubic-bezier(.36,.07,.19,.97) both;
        }
        @keyframes shake {
            10%, 90% { transform: translate3d(-1px, 0, 0); }
            20%, 80% { transform: translate3d(2px, 0, 0); }
            30%, 50%, 70% { transform: translate3d(-4px, 0, 0); }
            40%, 60% { transform: translate3d(4px, 0, 0); }
        }
        .full-screen-loader {
            position: fixed;
            top: 0;
            left: 0;
            width: 100vw;
            height: 100vh;
            background: rgba(255, 255, 255, 0.9);
            display: flex;
            justify-content: center;
            align-items: center;
            z-index: 9999;
            backdrop-filter: blur(5px);
        }
        .loader-content {
            text-align: center;
        }
        .loader-spinner {
            width: 50px;
            height: 50px;
            border: 3px solid #f3f3f3;
            border-top: 3px solid #4361ee;
            border-radius: 50%;
            animation: spin 1s linear infinite;
        }
        .loader-text {
            margin-top: 1rem;
            font-weight: 500;
            color: #4361ee;
        }
        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }
        .otp-inputs {
            display: flex;
            justify-content: space-between;
            margin-bottom: 1rem;
        }
        .otp-input {
            width: 40px;
            height: 40px;
            text-align: center;
            font-size: 1.2rem;
            border: 2px solid #e0e6ed;
            border-radius: 8px;
            background-color: rgba(255, 255, 255, 0.8);
        }
        .otp-input:focus {
            border-color: #4361ee;
            box-shadow: 0 0 0 0.2rem rgba(67, 97, 238, 0.25);
        }
        .resend-otp {
            display: flex;
            justify-content: end;
            align-items: center;
            margin-top: 1rem;
        }
        .back-icon {
            cursor: pointer;
            transition: transform 0.3s ease;
        }
        .back-icon:hover {
            transform: translateX(-5px);
        }
        .resend-link {
            color: #4361ee;
            text-decoration: none;
            font-size: 0.9rem;
            transition: color 0.3s ease;
        }
        .resend-link:hover {
            color: #3a4fd7;
            text-decoration: underline;
        }
        @media (max-width: 768px) {
            .card-body {
                padding: 2rem;
            }
        }
    </style>
</head>
<body>
    <div class="auth-container">
        <div x-data="loginForm()">
            <div class="card">
                <div class="card-body">
                    <div class="text-center mb-4">
                        <img src="{{ asset('asset/website/images/logo.png') }}" alt="Company Logo" class="logo">
                        <h2 class="text-primary">Welcome to Social Engineer Insurance Admin</h2>
                        <p class="welcome-text">Please sign in to access the admin dashboard</p>
                    </div>

                    <template x-if="step === 1">
                        <div class="fade-in">
                            <div class="mb-3">
                                <label class="form-label">Mobile Number</label>
                                <input type="tel" x-model="mobile" class="form-control" :class="{ 'shake': mobileError }" placeholder="Enter your mobile number">
                                <small class="text-danger" x-text="mobileError"></small>
                            </div>
                            <div class="mb-4">
                                <button @click="sendOtp" class="btn btn-primary w-100" :disabled="isLoading">
                                    <span x-show="!isLoading">Send OTP</span>
                                    <span x-show="isLoading">Sending...</span>
                                </button>
                            </div>
                        </div>
                    </template>

                    <template x-if="step === 2">
                        <div class="fade-in">
                            <div class="mb-3">
                                <div class="d-flex align-items-center mb-2">
                                    <svg @click="step = 1" class="back-icon mr-2" width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <path d="M19 12H5M5 12L12 19M5 12L12 5" stroke="#4361ee" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                    </svg>
                                    <label class="form-label mb-0 ml-2">Enter OTP sent to <span x-text="mobile"></span></label>
                                </div>
                                <div class="otp-inputs">
                                    <template x-for="(digit, index) in [0,1,2,3,4,5]">
                                        <input type="text" maxlength="1" class="otp-input" :key="index"
                                               @input="handleOtpInput($event, index)"
                                               @keydown.backspace="handleOtpBackspace($event, index)">
                                    </template>
                                </div>
                                <small class="text-danger" x-text="otpError"></small>
                            </div>
                            <div class="mb-3">
                                <button @click="verifyOtp" class="btn btn-primary w-100" :disabled="isLoading || otp.length !== 6">
                                    <span x-show="!isLoading">Verify OTP</span>
                                    <span x-show="isLoading">Verifying...</span>
                                </button>
                            </div>
                            <div class="resend-otp">
                                <div x-show="resendTimer > 0" class="timer">
                                    Resend OTP in <span x-text="resendTimer"></span>s
                                </div>
                                <a @click="resendOtp" class="resend-link" :class="{ 'disabled': resendTimer > 0 || isLoading }" x-show="resendTimer === 0" style="cursor: pointer">
                                    Resend OTP
                                </a>
                            </div>
                        </div>
                    </template>
                </div>
            </div>

            <!-- Full Screen Loader -->
            <div x-show="isLoading" class="full-screen-loader">
                <div class="loader-content">
                    <div class="loader-spinner"></div>
                    <p class="loader-text" x-text="loaderMessage"></p>
                </div>
            </div>
        </div>
    </div>

    <script>
        function loginForm() {
            return {
                step: 1,
                mobile: '',
                otp: '',
                isLoading: false,
                mobileError: '',
                otpError: '',
                loaderMessage: '',
                resendTimer: 0,
                resendInterval: null,

                sendOtp() {
                    if (!this.mobile) {
                        this.mobileError = 'Please enter your mobile number';
                        return;
                    }
                    this.isLoading = true;
                    this.mobileError = '';
                    this.loaderMessage = 'Sending OTP...';

                    axios.post('{{route("admin.SendOtp")}}', { phone_number: this.mobile })
                        .then(response => {
                            this.step = 2;
                            this.startResendTimer();
                        })
                        .catch(error => {
                            this.mobileError = error.response?.data?.message || 'Failed to send OTP';
                        })
                        .finally(() => {
                            this.isLoading = false;
                        });
                },
                handleOtpInput(event, index) {
                    const input = event.target;
                    const value = input.value;

                    if (value.length > 0) {
                        if (index < 5) {
                            input.nextElementSibling.focus();
                        }
                        this.otp = this.otp.substr(0, index) + value + this.otp.substr(index + 1);
                    }
                },
                handleOtpBackspace(event, index) {
                    if (event.key === 'Backspace' && index > 0 && event.target.value === '') {
                        event.target.previousElementSibling.focus();
                    }
                },
                verifyOtp() {
                    if (this.otp.length !== 6) {
                        this.otpError = 'Please enter a valid 6-digit OTP';
                        return;
                    }
                    this.isLoading = true;
                    this.otpError = '';
                    this.loaderMessage = 'Verifying OTP...';

                    axios.post('{{route("admin.verifyOtp")}}', { phone_number: this.mobile, otp: this.otp })
                        .then(response => {
                            window.location.href = '{{route("admin.analytics")}}';
                        })
                        .catch(error => {
                            this.otpError = error.response?.data?.message || 'Invalid OTP';
                        })
                        .finally(() => {
                            this.isLoading = false;
                        });
                },
                resendOtp() {
                    if (this.resendTimer === 0) {
                        this.sendOtp();
                    }
                },
                startResendTimer() {
                    this.resendTimer = 60;
                    this.resendInterval = setInterval(() => {
                        this.resendTimer--;
                        if (this.resendTimer <= 0) {
                            clearInterval(this.resendInterval);
                        }
                    }, 1000);
                }
            }
        }
    </script>
</body>
</html>
