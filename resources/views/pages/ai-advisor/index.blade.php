@extends('layouts.app')

@section('head')
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <style>
        .animate-fade-in {
            animation: fadeIn 0.5s ease-out;
        }

        .recommendation-card {
            opacity: 0;
            transform: translateY(10px);
            animation: fadeIn 0.5s ease-out forwards;
            animation-delay: calc(var(--index) * 0.1s);
        }

        .recommendation-section {
            transition: all 0.5s ease;
        }

        .recommendation-section.highlight {
            box-shadow: 0 0 0 5px rgba(79, 70, 229, 0.4);
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(10px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .highlight-result {
            animation: pulse 2s infinite;
        }

        @keyframes pulse {
            0% {
                box-shadow: 0 0 0 0 rgba(79, 70, 229, 0.4);
            }

            70% {
                box-shadow: 0 0 0 10px rgba(79, 70, 229, 0);
            }

            100% {
                box-shadow: 0 0 0 0 rgba(79, 70, 229, 0);
            }
        }
    </style>
@endsection

@section('content')
    <div class="bg-gray-50">
        <!-- Hero Section -->
        <div class="relative bg-gradient-to-r from-blue-600 to-indigo-600 py-16">
            <div class="absolute inset-0 bg-black/10"></div>
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative">
                <div class="text-center">
                    <h1 class="text-4xl tracking-tight font-extrabold text-white sm:text-5xl md:text-6xl">
                        <span class="block">Hệ Thống Chatbot AI</span>
                        <span class="block">Tư Vấn Thông Minh</span>
                    </h1>
                </div>
            </div>
        </div>

        <div class="max-w-8xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <!-- Chat Section -->
                <div class="lg:col-span-2 space-y-8">
                    <div class="bg-white rounded-2xl shadow-lg overflow-hidden">
                        <div class="h-[600px] flex flex-col">
                            <!-- Chat Messages -->
                            <div id="chat-messages" class="flex-1 overflow-y-auto p-6 space-y-6">
                                <!-- Welcome Message -->
                                <div class="flex items-start space-x-4">
                                    <div class="flex-shrink-0">
                                        <div
                                            class="h-10 w-10 rounded-xl bg-gradient-to-r from-blue-500 to-indigo-500 flex items-center justify-center">
                                            <svg class="h-6 w-6 text-white" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                                            </svg>
                                        </div>
                                    </div>
                                    <div class="flex-1">
                                        <div class="bg-gray-50 rounded-2xl p-6 shadow-sm">
                                            <div class="text-lg font-medium text-gray-900 mb-2">
                                                Xin chào! Tôi là trợ lý AI
                                            </div>
                                            <div class="text-gray-700">
                                                Tôi có thể giúp bạn:
                                                <ul class="mt-2 space-y-2">
                                                    <li class="flex items-center text-sm">
                                                        <svg class="h-5 w-5 text-blue-500 mr-2" fill="none"
                                                            stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                                stroke-width="2"
                                                                d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                        </svg>
                                                        Kết nối gia sư uy tín và hiệu quả với nhu cầu của bạn
                                                    </li>
                                                    <li class="flex items-center text-sm">
                                                        <svg class="h-5 w-5 text-blue-500 mr-2" fill="none"
                                                            stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                                stroke-width="2"
                                                                d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                        </svg>
                                                        Tìm tin đăng tuyển gia sư/lớp dạy
                                                    </li>
                                                    <li class="flex items-center text-sm">
                                                        <svg class="h-5 w-5 text-blue-500 mr-2" fill="none"
                                                            stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                                stroke-width="2"
                                                                d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                        </svg>
                                                        Hướng dẫn giải bài tập
                                                    </li>
                                                    <li class="flex items-center text-sm">
                                                        <svg class="h-5 w-5 text-blue-500 mr-2" fill="none"
                                                            stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                                stroke-width="2"
                                                                d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                        </svg>
                                                        Hỗ trợ và tư vấn
                                                    </li>
                                                </ul>
                                            </div>
                                            <div class="mt-4 text-sm text-gray-600">
                                                Hãy chia sẻ với tôi về nhu cầu của bạn!
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Chat Input -->
                            <div class="border-t border-gray-100 p-4 bg-white">
                                <form id="chat-form" class="flex items-center space-x-3" onsubmit="return false;">
                                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                    <div class="flex-1 min-w-0">
                                        <input type="text" id="message" name="message"
                                            class="block w-full rounded-xl border-0 px-4 py-3 bg-gray-50 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-500 focus:ring-2 focus:ring-inset focus:ring-blue-600 sm:text-sm sm:leading-6"
                                            placeholder="Nhập tin nhắn của bạn...">
                                    </div>
                                    <button type="submit"
                                        class="inline-flex items-center justify-center rounded-xl bg-blue-600 px-4 py-3 text-sm font-semibold text-white shadow-sm hover:bg-blue-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-blue-600 transition-all duration-200">
                                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8" />
                                        </svg>
                                    </button>
                                    <button type="button" id="summarize-btn" style="display: none;"
                                        class="inline-flex items-center justify-center rounded-xl bg-green-600 px-4 py-3 text-sm font-semibold text-white shadow-sm hover:bg-green-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-green-600 transition-all duration-200">
                                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                                        </svg>
                                        <span class="ml-2">Tổng kết</span>
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Recommendations Section -->
                <div class="lg:col-span-1">
                    <div class="bg-white rounded-2xl shadow-lg overflow-hidden recommendation-section">
                        <div class="p-6">
                            <h2 class="text-lg font-semibold text-gray-900 mb-4">Kết Quả</h2>
                            <div id="recommendations" class="space-y-4">
                                <!-- Empty State -->
                                <div class="text-center py-8 no-results">
                                    <div
                                        class="inline-flex items-center justify-center w-12 h-12 rounded-full bg-blue-100 mb-4">
                                        <svg class="h-6 w-6 text-blue-600" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z" />
                                        </svg>
                                    </div>
                                    <h3 class="text-sm font-medium text-gray-900">Chưa có kết quả</h3>
                                    <p class="mt-1 text-sm text-gray-500">Hãy bắt đầu trò chuyện để nhận kết quả phù hợp.
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {

        window.App = {
            user: @json($user),
            tutorId: @json($tutorId),
        };
        function getCSRFToken() {
            return document.querySelector('meta[name="csrf-token"]').getAttribute('content');
        }

        // Reset conversation khi load trang
        fetch("{{ route('ai-advisor.reset') }}", {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': getCSRFToken(),
                'Accept': 'application/json'
            }
        })
            .then(response => response.json())
            .then(data => {
                console.log("Conversation reset successfully");
            })
            .catch(error => {
                console.error("Error resetting conversation:", error);
            });

        const chatForm = document.getElementById('chat-form');
        const chatMessages = document.getElementById('chat-messages');
        const messageInput = document.getElementById('message');
        const summarizeBtn = document.getElementById('summarize-btn');
        const recommendations = document.getElementById('recommendations');

        if (!chatForm || !chatMessages || !messageInput || !summarizeBtn || !recommendations) {
            console.error('Không tìm thấy các elements cần thiết');
            return;
        }

        function showLoading() {
            const loadingDiv = document.createElement('div');
            loadingDiv.className = 'flex items-start space-x-4 animate-fade-in loading-message';
            loadingDiv.innerHTML = `
                <div class="flex-shrink-0">
                    <div class="h-10 w-10 rounded-xl bg-gradient-to-r from-blue-500 to-indigo-500 flex items-center justify-center">
                        <svg class="h-6 w-6 text-white animate-spin" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                    </div>
                </div>
                <div class="flex-1">
                    <div class="bg-gray-50 rounded-2xl p-4 shadow-sm">
                        <div class="flex space-x-2">
                            <div class="w-2 h-2 bg-blue-400 rounded-full animate-bounce"></div>
                            <div class="w-2 h-2 bg-blue-400 rounded-full animate-bounce" style="animation-delay: 0.2s"></div>
                            <div class="w-2 h-2 bg-blue-400 rounded-full animate-bounce" style="animation-delay: 0.4s"></div>
                        </div>
                    </div>
                </div>
            `;
            chatMessages.appendChild(loadingDiv);
            chatMessages.scrollTop = chatMessages.scrollHeight;
            return loadingDiv;
        }

        function showRecommendationsLoading() {
            const recommendationsDiv = document.getElementById('recommendations');
            recommendationsDiv.innerHTML = `
                <div class="space-y-4 animate-fade-in">
                    <!-- Loading skeleton cards -->
                    ${Array(3).fill(0).map((_, i) => `
                        <div class="bg-white border border-gray-200 rounded-2xl shadow-sm p-6" style="animation-delay: ${i * 0.1}s">
                            <div class="flex items-center space-x-4 mb-4">
                                <div class="skeleton h-12 w-12 rounded-lg"></div>
                                <div class="flex-1 space-y-2">
                                    <div class="skeleton h-4 w-3/4 rounded"></div>
                                    <div class="skeleton h-3 w-1/2 rounded"></div>
                                </div>
                            </div>
                            <div class="space-y-2">
                                <div class="skeleton h-3 w-full rounded"></div>
                                <div class="skeleton h-3 w-5/6 rounded"></div>
                                <div class="skeleton h-3 w-4/6 rounded"></div>
                            </div>
                            <div class="mt-4 pt-4 border-t">
                                <div class="skeleton h-10 w-full rounded-lg"></div>
                            </div>
                        </div>
                    `).join('')}
                    <div class="text-center py-4">
                        <div class="inline-flex items-center space-x-2 text-blue-600">
                            <svg class="animate-spin h-5 w-5" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                            <span class="text-sm font-medium">Đang tìm kiếm kết quả phù hợp...</span>
                        </div>
                    </div>
                </div>
            `;
        }

        function removeLoading() {
            const loadingMessages = document.querySelectorAll('.loading-message');
            loadingMessages.forEach(msg => msg.remove());
        }


        function appendMessage(message, isUser = false) {
            const messageDiv = document.createElement('div');
            messageDiv.className = 'flex items-start space-x-4 animate-fade-in';

            const avatarBg = isUser ? 'bg-blue-500' : 'bg-gradient-to-r from-blue-500 to-indigo-500';

            messageDiv.innerHTML = `
                <div class="flex-shrink-0">
                    <div class="h-10 w-10 rounded-xl ${avatarBg} flex items-center justify-center transform hover:scale-110 transition-transform duration-200">
                        <svg class="h-6 w-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="${isUser ? 'M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z' : 'M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z'}" />
                        </svg>
                    </div>
                </div>
                <div class="flex-1">
                    <div class="bg-gray-50 rounded-2xl p-4 shadow-sm">
                        <div class="text-gray-900 whitespace-pre-line">${message}</div>
                    </div>
                </div>
            `;

            chatMessages.appendChild(messageDiv);
            chatMessages.scrollTop = chatMessages.scrollHeight;
        }

        chatForm.addEventListener('submit', async function (e) {
            e.preventDefault();
            e.stopPropagation();

            const message = messageInput.value.trim();
            if (!message) return false;

            appendMessage(message, true);
            messageInput.value = '';

            const loadingElement = showLoading();

            try {
                const response = await fetch('/ai-advisor/chat', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': getCSRFToken(),
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest'
                    },
                    body: JSON.stringify({
                        message: message,
                        type: 'chat'
                    })
                });

                if (!response.ok) {
                    const data = await response.json().catch(() => ({}));
                    throw new Error(data.message || 'Network response was not ok');
                }

                const data = await response.json();

                if (data.message) {
                    appendMessage(data.message, false);
                }

                if (data.recommendations && data.recommendations.length > 0) {
                    updateRecommendations(data.recommendations);
                    summarizeBtn.style.display = 'none';
                } else {
                    summarizeBtn.style.display = 'none';
                    fetch('/ai-advisor/chat', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': getCSRFToken(),
                            'Accept': 'application/json',
                            'X-Requested-With': 'XMLHttpRequest'
                        },
                        body: JSON.stringify({ type: 'summarize' })
                    })
                        .then(res => res.json())
                        .then(sumData => {
                            if (sumData.recommendations && sumData.recommendations.length > 0) {
                                updateRecommendations(sumData.recommendations);
                            }
                        });
                }
            } catch (error) {
                console.error('Error:', error);
                appendMessage('Xin lỗi, đã có lỗi xảy ra. Vui lòng thử lại.', false);
            } finally {
                if (loadingElement) {
                    loadingElement.remove();
                }
                removeLoading();
            }

            return false;
        });

        summarizeBtn.addEventListener('click', async function () {
            updateRecommendations([]);

            const existingRecommendationElements = document.querySelectorAll('.recommendation-card');
            if (existingRecommendationElements.length > 0) {
                const recommendationSection = document.querySelector('.recommendation-section');
                if (recommendationSection) {
                    recommendationSection.classList.add('highlight');
                    recommendationSection.scrollIntoView({ behavior: 'smooth' });
                    setTimeout(() => {
                        recommendationSection.classList.remove('highlight');
                    }, 3000);
                }
                return;
            }

            showRecommendationsLoading();

            const loadingElement = showLoading();
            appendMessage("Đang tìm kiếm kết quả phù hợp... Vui lòng đợi trong giây lát.", false);

            try {
                const token = getCSRFToken();
                if (!token) {
                    throw new Error('CSRF token not found');
                }

                const response = await fetch('/ai-advisor/chat', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': token,
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest'
                    },
                    body: JSON.stringify({
                        type: 'summarize'
                    })
                });

                const data = await response.json();

                if (data.summary) {
                    appendMessage(data.summary, false);
                }

                if (data.recommendations && data.recommendations.length > 0) {
                    const recommendationSection = document.querySelector('.recommendation-section');
                    if (recommendationSection) {
                        recommendationSection.classList.add('highlight');
                        recommendationSection.scrollIntoView({ behavior: 'smooth' });
                        setTimeout(() => {
                            recommendationSection.classList.remove('highlight');
                        }, 3000);
                    }

                    updateRecommendations(data.recommendations);
                    appendMessage(`Đã tìm thấy ${data.recommendations.length} kết quả phù hợp với yêu cầu của bạn. Vui lòng xem bên phải.`, false);
                } else {
                    appendMessage("Không tìm thấy kết quả phù hợp trong hệ thống. Vui lòng thử lại với yêu cầu khác.", false);
                }
            } catch (error) {
                console.error('Error:', error);
                appendMessage('Xin lỗi, đã có lỗi xảy ra khi tổng kết.', false);
            } finally {
                if (loadingElement) {
                    loadingElement.remove();
                }
                removeLoading();
            }
        });

        function updateRecommendations(recommendations) {
            const recommendationsDiv = document.getElementById('recommendations');
            if (!recommendationsDiv) {
                console.error('Element #recommendations not found');
                return;
            }
            recommendationsDiv.innerHTML = '';

            if (!recommendations || recommendations.length === 0) {
                recommendationsDiv.innerHTML = `
                    <div class="text-center p-8">
                        <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <h3 class="mt-2 text-sm font-medium text-gray-900">Không tìm thấy kết quả phù hợp</h3>
                        <p class="mt-1 text-sm text-gray-500">Vui lòng cung cấp thêm thông tin về nhu cầu của bạn.</p>
                    </div>
                `;
                return;
            }

            // Helper functions for rendering
            function renderJobPost(item, currentTutorId) {
                const isLoggedIn = !!window.App?.user;
                const isTutor = !!currentTutorId;
                const applied = Array.isArray(item.applied_tutor_ids) && item.applied_tutor_ids.includes(currentTutorId);

                return `
                    <div class="bg-white border border-gray-200 rounded-2xl shadow-sm hover:shadow-md transition p-6 flex flex-col">
                        <div class="flex items-center justify-between mb-3">
                            <h3 class="text-lg font-bold text-gray-900 font-serif">
                                ${item.subject || 'Môn học'} - ${item.class_level || 'Cấp học'}
                            </h3>
                            <span class="px-3 py-1 text-xs rounded-full ${
                                item.mode === 'online' ? 'bg-blue-50 text-blue-600' : 'bg-green-50 text-green-600'
                            }">
                                ${item.mode === 'online' ? 'Online' : 'Offline'}
                            </span>
                        </div>

                        <p class="text-gray-600 text-sm mb-4 line-clamp-2 italic">
                            "${item.goal || item.title || ''}"
                        </p>

                        <dl class="grid grid-cols-2 gap-x-4 gap-y-2 text-sm text-gray-700 flex-grow">
                            <div><dt class="font-medium">Số buổi/tuần:</dt><dd>${item.sessions_per_week || 'N/A'} buổi</dd></div>
                            <div><dt class="font-medium">Thời lượng:</dt><dd>${item.session_length_min || 'N/A'} phút</dd></div>
                            <div>
                                <dt class="font-medium">Ngân sách:</dt>
                                <dd>${
                                    item.budget_min
                                        ? `${item.budget_min.toLocaleString()} - ${item.budget_max.toLocaleString()} / ${item.budget_unit || 'buổi'}`
                                        : 'Thoả thuận'
                                }</dd>
                            </div>
                            ${
                                item.deadline_at
                                    ? `<div><dt class="font-medium">Hạn chót:</dt><dd>${new Date(item.deadline_at).toLocaleDateString('vi-VN')}</dd></div>`
                                    : ''
                            }
                        </dl>

                        <div class="mt-6 flex justify-between items-center border-t pt-4 gap-3">
                            <a href="/tutor-posts/${item.id}" class="text-sm font-medium text-indigo-600 hover:underline">
                                Xem chi tiết
                            </a>
                            ${
                                !isLoggedIn
                                    ? `<a href="/login" class="text-sm text-gray-500 hover:underline">Đăng nhập để ứng tuyển</a>`
                                    : !isTutor
                                        ? `<span class="text-sm text-gray-500">Chỉ dành cho gia sư</span>`
                                        : applied
                                            ? `<span class="inline-flex items-center px-3 py-1.5 rounded-lg bg-gray-200 text-gray-600 text-sm shadow">
                                                Đã ứng tuyển
                                            </span>`
                                            : `<button
                                                onclick="applyToJob(${item.id})"
                                                class="inline-flex items-center px-3 py-1.5 rounded-lg bg-gradient-to-r from-indigo-600 to-purple-600 text-white hover:from-indigo-700 hover:to-purple-700 text-sm shadow">
                                                Ứng tuyển
                                            </button>`
                            }
                        </div>
                    </div>
                `;
            }

            // Hàm gọi AJAX ứng tuyển
            function applyToJob(jobId) {
                if (!window.App?.user) {
                    alert('Vui lòng đăng nhập để ứng tuyển.');
                    window.location.href = '/login';
                    return;
                }

                if (!window.App?.tutorId) {
                    alert('Chỉ tài khoản gia sư mới có thể ứng tuyển.');
                    return;
                }

                fetch('/tutors/apply', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    },
                    body: JSON.stringify({ job_id: jobId }),
                })
                .then(res => {
                    if (!res.ok) throw new Error('Ứng tuyển thất bại');
                    return res.json();
                })
                .then(data => {
                    alert('Ứng tuyển thành công!');
                    window.location.reload();
                })
                .catch(err => {
                    console.error(err);
                    alert('Đã xảy ra lỗi khi ứng tuyển. Vui lòng thử lại sau.');
                });
            }
            window.applyToJob = applyToJob;

            function renderSupport(item) {
                return `
                    <div class="flex items-center space-x-4">
                        <div class="flex-shrink-0">
                            <img src="${item.avatar || '/images/support.png'}" alt="Support" class="h-12 w-12 rounded-lg object-cover">
                        </div>
                        <div class="flex-1 min-w-0">
                            <h4 class="text-sm font-semibold text-gray-900">${item.name}</h4>
                        </div>
                    </div>
                    <div class="mt-3 text-sm text-gray-600 space-y-2">
                        ${item.reason}
                    </div>
                `;
            }

            function renderAcademicAnswer(item) {
                // Xác định avatar dựa trên môn học
                const subjectAvatars = {
                    'Toán': '/images/ai-math.png',
                    'Lý': '/images/ai-physics.png',
                    'Hóa': '/images/ai-chemistry.png',
                    'Văn': '/images/ai-literature.png',
                    'Anh': '/images/ai-english.png',
                    'Lịch sử': '/images/ai-history.png',
                    'khác': '/images/ai-academic.png'
                };
                const avatar = item.avatar || subjectAvatars[item.subject || 'khác'] || '/images/ai-academic.png';

                // Xử lý nội dung reason để hỗ trợ Markdown và LaTeX
                let reason = item.reason || '';
                // Thay thế ký hiệu LaTeX (ví dụ: \(...\) hoặc $...$) để MathJax render
                reason = reason.replace(/\\\((.*?)\\\)/g, '<span class="mathjax">\\($1\\)</span>');
                // Chuyển các bước giải (1., 2., v.v.) thành danh sách HTML
                reason = reason.replace(/^\d+\.\s/gm, '<li>').replace(/<li>(.*?)(<br>|$)/g, '<li>$1</li>');
                // Thay thế các thẻ Markdown cơ bản (in đậm **...**)
                reason = reason.replace(/\*\*(.*?)\*\*/g, '<strong>$1</strong>');
                // Thay thế xuống dòng thành <br> để giữ định dạng
                reason = reason.replace(/\n/g, '<br>');

                // Tạo HTML với giao diện cải tiến
                return `
                    <div class="flex items-start space-x-4 p-4 bg-gray-50 rounded-lg shadow-sm">
                        <div class="flex-shrink-0">
                            <img src="${avatar}" alt="${item.subject || 'Academic'} Answer" class="h-12 w-12 rounded-lg object-cover">
                        </div>
                        <div class="flex-1 min-w-0">
                            <h4 class="text-sm font-semibold text-gray-900">${item.name || 'AI Trả Lời Học Thuật'}</h4>
                            <div class="mt-2 text-sm text-gray-600 whitespace-pre-line">
                                ${reason}
                            </div>
                        </div>
                    </div>
                `;
            }
            
            function renderTutor(item) {
                const matchingPercent = Math.round(item.matching_score * 100);
                const formattedPrice = new Intl.NumberFormat('vi-VN', { style: 'currency', currency: 'VND' }).format(item.hourly_rate);

                // Chỉ render nút xem hồ sơ nếu có tutorId trong window.App
                const profileLink = (window.App && window.App.tutorId)
                    ? `
                        <div class="mt-4">
                            <a href="/tutors/${item.id}" 
                            class="inline-flex justify-center rounded-lg text-sm font-semibold py-2.5 px-4 bg-blue-600 text-white hover:bg-blue-500 w-full">
                                <span>Xem hồ sơ</span>
                                <svg class="w-5 h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6" />
                                </svg>
                            </a>
                        </div>
                    `
                    : '';

                return `
                    <div class="flex items-center space-x-4">
                        <div class="flex-shrink-0">
                            <img src="${item.avatar || '/images/default-avatar.png'}" alt="${item.name}" class="h-12 w-12 rounded-lg object-cover">
                        </div>
                        <div class="flex-1 min-w-0">
                            <h4 class="text-sm font-semibold text-gray-900 truncate">${item.name}</h4>
                            <p class="text-sm text-blue-600 mt-1">
                                ${formattedPrice}/giờ
                                <span class="ml-2 text-green-600">${matchingPercent}% phù hợp</span>
                            </p>
                        </div>
                    </div>
                    <div class="mt-3 text-sm text-gray-600">
                        ${item.reason}
                    </div>
                    ${profileLink}
                `;
            }

            // Set section title
            const resultType = recommendations[0]?.type || 'tutor';
            const recommendationTitle = document.querySelector('.recommendation-section h2');
            if (recommendationTitle) {
                let titleText = 'Kết Quả';
                if (resultType === 'job_post') {
                    titleText = `Tin Đăng Tuyển (${recommendations.length})`;
                } else if (resultType === 'tutor') {
                    titleText = `Đề Xuất Gia Sư (${recommendations.length})`;
                } else if (resultType === 'support') {
                    titleText = 'Thông Tin Hỗ Trợ';
                } else if (resultType === 'math_solution') {
                    titleText = 'Hướng Dẫn Giải Bài';
                }
                recommendationTitle.textContent = titleText;
                recommendationTitle.classList.add('highlight-result');
                setTimeout(() => {
                    recommendationTitle.classList.remove('highlight-result');
                }, 3000);
            }

            const currentTutorId = window.App?.tutorId;
            // Render cards
            recommendations.forEach((item, index) => {
                const card = document.createElement('div');
                card.className = 'bg-white p-4 rounded-xl shadow-md hover:shadow-lg transition-all duration-200 recommendation-card mb-4';
                card.style.setProperty('--index', index);
                let html = '';
                const type = item.budget_min !== undefined ? 'job_post' : item.reason && item.reason.includes('hotline') ? 'support' : item.reason && item.reason.includes('bài toán') ? 'math_solution' : 'tutor';
                if (type === 'job_post') html = renderJobPost(item, currentTutorId);
                else if (type === 'support') html = renderSupport(item);
                else if (type === 'academic_question') html = renderAcademicAnswer(item);
                else html = renderTutor(item);
                card.innerHTML = html;
                recommendationsDiv.appendChild(card);
                card.scrollIntoView({ behavior: 'smooth' });
            });
        }
    });
</script>
@endpush