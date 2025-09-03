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
    <div class="min-h-screen bg-gray-50">
        <!-- Hero Section với gradient đẹp mắt -->
        <div class="relative bg-gradient-to-r from-blue-600 to-indigo-600 py-16">
            <div class="absolute inset-0 bg-black/10"></div>
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative">
                <div class="text-center">
                    <h1 class="text-4xl tracking-tight font-extrabold text-gray-900 sm:text-5xl md:text-6xl">
                        <span class="block xl:inline">Tìm Gia Sư</span>
                        <span class="block text-indigo-600 xl:inline">Phù Hợp Nhất</span>
                    </h1>
                </div>
            </div>
        </div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
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
                                        <div class="h-10 w-10 rounded-xl bg-gradient-to-r from-blue-500 to-indigo-500 flex items-center justify-center">
                                            <svg class="h-6 w-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
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
                                                        <svg class="h-5 w-5 text-blue-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                        </svg>
                                                        Tìm gia sư phù hợp với nhu cầu của bạn
                                                    </li>
                                                    <li class="flex items-center text-sm">
                                                        <svg class="h-5 w-5 text-blue-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                        </svg>
                                                        Tư vấn phương pháp học tập hiệu quả
                                                    </li>
                                                    <li class="flex items-center text-sm">
                                                        <svg class="h-5 w-5 text-blue-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                        </svg>
                                                        Gợi ý lộ trình học tập phù hợp
                                                    </li>
                                                </ul>
                                            </div>
                                            <div class="mt-4 text-sm text-gray-600">
                                                Hãy chia sẻ với tôi về nhu cầu học tập của bạn!
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
                                        <input type="text" id="message" name="message" class="block w-full rounded-xl border-0 px-4 py-3 bg-gray-50 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-500 focus:ring-2 focus:ring-inset focus:ring-blue-600 sm:text-sm sm:leading-6" placeholder="Nhập tin nhắn của bạn...">
                                    </div>
                                    <button type="submit" class="inline-flex items-center justify-center rounded-xl bg-blue-600 px-4 py-3 text-sm font-semibold text-white shadow-sm hover:bg-blue-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-blue-600 transition-all duration-200">
                                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8" />
                                        </svg>
                                    </button>
                                    <button type="button" id="summarize-btn" class="inline-flex items-center justify-center rounded-xl bg-green-600 px-4 py-3 text-sm font-semibold text-white shadow-sm hover:bg-green-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-green-600 transition-all duration-200">
                                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
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
                            <h2 class="text-lg font-semibold text-gray-900 mb-4">Đề Xuất Gia Sư</h2>
                            <div id="recommendations" class="space-y-4">
                                <!-- Empty State -->
                                <div class="text-center py-8 no-results">
                                    <div class="inline-flex items-center justify-center w-12 h-12 rounded-full bg-blue-100 mb-4">
                                        <svg class="h-6 w-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z" />
                                        </svg>
                                    </div>
                                    <h3 class="text-sm font-medium text-gray-900">Chưa có đề xuất</h3>
                                    <p class="mt-1 text-sm text-gray-500">Hãy bắt đầu trò chuyện để nhận đề xuất phù hợp.</p>
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
    document.addEventListener('DOMContentLoaded', function() {
        // Function to get CSRF token
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

        // Hàm hiển thị loading
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

        function removeLoading() {
            const loadingMessages = document.querySelectorAll('.loading-message');
            loadingMessages.forEach(msg => msg.remove());
        }

        // Hàm hiển thị tin nhắn
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
                        <div class="text-gray-900">${message}</div>
                    </div>
                </div>
            `;

            chatMessages.appendChild(messageDiv);
            chatMessages.scrollTop = chatMessages.scrollHeight;
        }

        // Xử lý gửi tin nhắn
        chatForm.addEventListener('submit', async function(e) {
            e.preventDefault();
            e.stopPropagation();
            
            console.log('Form submitted');
            
            const message = messageInput.value.trim();
            if (!message) return false;

            // Hiển thị tin nhắn người dùng
            appendMessage(message, true);
            messageInput.value = '';

            // Hiển thị loading
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

                console.log('Response status:', response.status);
                
                if (!response.ok) {
                    const data = await response.json().catch(() => ({}));
                    console.error('Error response:', data);
                    throw new Error(data.message || 'Network response was not ok');
                }

                const contentType = response.headers.get('content-type');
                if (!contentType || !contentType.includes('application/json')) {
                    console.error('Invalid response type:', contentType);
                    throw new Error('Invalid response type');
                }

                const data = await response.json();
                console.log('Response data:', data);

                if (data.message) {
                    appendMessage(data.message, false);
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

// Xử lý nút tổng kết
summarizeBtn.addEventListener('click', async function() {
    // Xóa danh sách gia sư cũ
    updateRecommendations([]);

    // Kiểm tra xem đã có kết quả hiển thị chưa
    const existingRecommendationElements = document.querySelectorAll('.recommendation-card');
    if (existingRecommendationElements.length > 0) {
        // Nếu đã có kết quả, chỉ cần scroll để hiển thị kết quả đó
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

    const loadingElement = showLoading();
    appendMessage("Đang tìm kiếm gia sư phù hợp... Vui lòng đợi trong giây lát.", false);

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
        console.log('Summarize response:', data);

        if (data.summary) {
            appendMessage(data.summary, false);
        }

        if (data.recommendations && data.recommendations.length > 0) {
            // Remove loading class and show recommendations
            console.log(`Found ${data.recommendations.length} recommendations`);
            try {
                const recommendationSection = document.querySelector('.recommendation-section');
                if (recommendationSection) {
                    recommendationSection.classList.add('highlight');
                    recommendationSection.scrollIntoView({ behavior: 'smooth' });
                    setTimeout(() => {
                        recommendationSection.classList.remove('highlight');
                    }, 3000);
                }
            } catch (e) {
                console.error('Error highlighting recommendation section:', e);
            }
            
            updateRecommendations(data.recommendations);
            appendMessage(`Đã tìm thấy ${data.recommendations.length} gia sư phù hợp với yêu cầu của bạn. Vui lòng xem bên phải.`, false);
        } else {
            console.log('No recommendations found');
            appendMessage("Không tìm thấy gia sư phù hợp trong hệ thống. Vui lòng thử lại với yêu cầu khác.", false);
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

// Hàm cập nhật đề xuất gia sư
function updateRecommendations(recommendations) {
    const recommendationsDiv = document.getElementById('recommendations');
    if (!recommendationsDiv) {
        console.error('Element #recommendations not found');
        return;
    }
    
    // Xóa nội dung cũ
    recommendationsDiv.innerHTML = '';

    if (!recommendations || recommendations.length === 0) {
        recommendationsDiv.innerHTML = `
            <div class="text-center p-8">
                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <h3 class="mt-2 text-sm font-medium text-gray-900">Không tìm thấy gia sư phù hợp</h3>
                <p class="mt-1 text-sm text-gray-500">Vui lòng cung cấp thêm thông tin về nhu cầu của bạn.</p>
            </div>
        `;
        return;
    }

    console.log('Rendering recommendations:', recommendations);

    // Cập nhật tiêu đề section
    try {
        const recommendationTitle = document.querySelector('.recommendation-section h2');
        if (recommendationTitle) {
            recommendationTitle.textContent = `Đề Xuất Gia Sư (${recommendations.length})`;
            recommendationTitle.classList.add('highlight-result');
            setTimeout(() => {
                recommendationTitle.classList.remove('highlight-result');
            }, 3000);
        }
    } catch (e) {
        console.log('Error updating recommendation title:', e);
    }

    try {
        recommendations.forEach((tutor, index) => {
            const tutorCard = document.createElement('div');
            tutorCard.className = 'bg-white p-4 rounded-xl shadow-md hover:shadow-lg transition-all duration-200 recommendation-card mb-4';
            tutorCard.style.setProperty('--index', index);
            
            const matchingPercent = Math.round(tutor.matching_score * 100);
            const formattedPrice = new Intl.NumberFormat('vi-VN', { 
                style: 'currency', 
                currency: 'VND' 
            }).format(tutor.hourly_rate);

            tutorCard.innerHTML = `
                <div class="flex items-center space-x-4">
                    <img src="${tutor.avatar || '/images/default-avatar.png'}" 
                         alt="${tutor.name}" 
                         class="h-12 w-12 rounded-full object-cover">
                    <div class="flex-1 min-w-0">
                        <p class="text-sm font-medium text-gray-900 truncate">
                            ${tutor.name}
                        </p>
                        <p class="text-sm text-gray-500 truncate">
                            ${tutor.subjects ? tutor.subjects.join(', ') : ''}
                        </p>
                    </div>
                    <div class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium ${matchingPercent >= 90 ? 'bg-green-100 text-green-800' : 'bg-blue-100 text-blue-800'}">
                        ${matchingPercent}% phù hợp
                    </div>
                </div>

                <div class="mt-3 text-sm text-gray-600">
                    ${tutor.reason || 'Gia sư phù hợp với yêu cầu của bạn'}
                </div>

                <div class="mt-4 flex justify-between items-center">
                    <div class="flex items-center">
                        <svg class="h-5 w-5 text-yellow-400" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                        </svg>
                        <span class="ml-1 text-sm text-gray-600">${tutor.rating || '5.0'}/5.0</span>
                    </div>
                    <span class="text-sm font-medium text-gray-900">${formattedPrice}/giờ</span>
                </div>

                <a href="/tutors/${tutor.id}" 
                   class="mt-4 block w-full text-center px-4 py-2 border border-transparent text-sm font-medium rounded-lg text-white bg-blue-600 hover:bg-blue-500 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-all duration-200">
                    Xem chi tiết
                </a>
            `;
            
            recommendationsDiv.appendChild(tutorCard);
        });
    } catch (err) {
        console.error('Error processing recommendations:', err);
        recommendationsDiv.innerHTML = `
            <div class="text-center p-8">
                <svg class="mx-auto h-12 w-12 text-red-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                </svg>
                <h3 class="mt-2 text-sm font-medium text-gray-900">Đã xảy ra lỗi khi hiển thị gia sư</h3>
                <p class="mt-1 text-sm text-gray-500">Vui lòng thử lại sau.</p>
            </div>
        `;
    }
}
    });
</script>
@endpush