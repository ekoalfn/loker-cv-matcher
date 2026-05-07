<x-layout>
    <x-slot:title>CV Matcher - Temukan Pekerjaan yang Tepat | Lamaraja</x-slot:title>
    <x-slot:description>Upload CV kamu dan biarkan AI kami menganalisis skill, pengalaman, dan kelebihanmu untuk mencocokkan dengan lowongan kerja yang paling relevan.</x-slot:description>

    <div class="relative bg-slate-50 min-h-screen pb-20 overflow-hidden">
        {{-- Background Decorations --}}
        <div class="absolute top-0 left-0 w-full h-96 bg-gradient-to-b from-white to-transparent z-0"></div>
        <div class="absolute -top-24 -right-24 w-96 h-96 bg-emerald-100/50 rounded-full blur-3xl z-0 pointer-events-none"></div>
        <div class="absolute top-48 -left-24 w-72 h-72 bg-emerald-50/50 rounded-full blur-3xl z-0 pointer-events-none"></div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10 pt-16 lg:pt-24">
            
            {{-- Hero Section --}}
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 lg:gap-8 items-start mb-24">
                {{-- Left: Text & Upload --}}
                <div class="flex flex-col">
                    <h1 class="text-4xl lg:text-6xl font-[family-name:var(--font-display)] font-bold text-slate-900 tracking-tight leading-tight mb-6">
                        Find jobs that<br>
                        truly <span class="text-emerald-600">match</span> your CV
                    </h1>
                    <p class="text-lg text-slate-600 mb-10 max-w-lg leading-relaxed">
                        Upload your CV and let our AI analyze your skills, experience, and strengths to match you with the most relevant jobs.
                    </p>

                    {{-- Upload Card --}}
                    <div class="bg-white rounded-3xl p-8 shadow-[0_8px_30px_rgb(0,0,0,0.04)] border border-slate-100 max-w-md relative group">
                        {{-- Decorative border effect on hover --}}
                        <div class="absolute -inset-0.5 bg-gradient-to-br from-emerald-400 to-emerald-600 rounded-[1.6rem] opacity-0 group-hover:opacity-10 transition-opacity duration-500 blur"></div>
                        
                        <div class="relative">
                            <div class="border-2 border-dashed border-emerald-200 bg-emerald-50/30 rounded-2xl p-8 flex flex-col items-center justify-center text-center transition-colors hover:bg-emerald-50/50">
                                <div class="w-16 h-16 bg-emerald-100 text-emerald-600 rounded-2xl flex items-center justify-center mb-4 shadow-sm">
                                    <svg class="w-8 h-8" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"/>
                                    </svg>
                                </div>
                                <h3 class="text-lg font-semibold text-slate-900 mb-2">Upload Your CV</h3>
                                <p class="text-sm text-slate-500 mb-6">Drag and drop your file here, or <button class="text-emerald-600 font-medium hover:underline focus:outline-none">browse</button></p>
                                <p class="text-xs text-slate-400 mb-6">PDF, DOCX (Max. 5MB)</p>
                                
                                <button class="w-full py-3.5 bg-emerald-600 hover:bg-emerald-700 text-white font-semibold rounded-xl transition-all shadow-sm hover:shadow-md active:scale-[0.98]">
                                    Choose File
                                </button>
                            </div>
                            
                            <div class="flex items-center justify-center gap-2 mt-6 text-sm text-slate-500">
                                <svg class="w-4 h-4 text-emerald-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                                </svg>
                                Your data is secure and will not be shared with anyone.
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Right: Analysis Summary (Mockup) --}}
                <div class="relative lg:ml-auto w-full max-w-lg mt-8 lg:mt-0">
                    {{-- Floating element top right --}}
                    <div class="absolute -top-12 -right-8 z-20 animate-bounce-slow hidden sm:block">
                        <div class="bg-white rounded-2xl shadow-xl p-3 flex items-center gap-3">
                            <div class="w-12 h-12 bg-emerald-100 rounded-xl flex items-center justify-center text-emerald-600 shrink-0">
                                <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                                </svg>
                            </div>
                            <div class="space-y-2 w-24">
                                <div class="h-2 bg-slate-200 rounded w-full"></div>
                                <div class="h-2 bg-slate-200 rounded w-2/3"></div>
                            </div>
                        </div>
                    </div>

                    {{-- Main Card --}}
                    <div class="bg-white rounded-3xl p-8 shadow-[0_20px_40px_rgb(0,0,0,0.06)] border border-slate-100 relative z-10">
                        <h3 class="text-sm font-bold text-emerald-600 mb-8 uppercase tracking-wider">CV Analysis Summary</h3>
                        
                        <div class="flex flex-col sm:flex-row gap-8 mb-8 items-center sm:items-start">
                            {{-- Circular Progress --}}
                            <div class="relative w-32 h-32 shrink-0">
                                <svg class="w-full h-full transform -rotate-90" viewBox="0 0 100 100">
                                    <circle class="text-slate-100 stroke-current" stroke-width="12" cx="50" cy="50" r="40" fill="transparent"></circle>
                                    <circle class="text-emerald-500 stroke-current" stroke-width="12" stroke-linecap="round" cx="50" cy="50" r="40" fill="transparent" stroke-dasharray="251.2" stroke-dashoffset="37.68"></circle> {{-- 85% --}}
                                </svg>
                                <div class="absolute inset-0 flex flex-col items-center justify-center">
                                    <span class="text-xl font-bold text-slate-900">Strong</span>
                                    <span class="text-[0.65rem] font-medium text-slate-500 uppercase tracking-wide mt-0.5">Overall Fit</span>
                                </div>
                            </div>
                            
                            {{-- Progress Bars --}}
                            <div class="flex-1 w-full space-y-5 pt-2">
                                <div>
                                    <div class="flex justify-between text-sm font-semibold mb-2">
                                        <span class="text-slate-700">Skills Match</span>
                                        <span class="text-slate-900">85%</span>
                                    </div>
                                    <div class="h-2 w-full bg-slate-100 rounded-full overflow-hidden">
                                        <div class="h-full bg-emerald-500 rounded-full w-[85%]"></div>
                                    </div>
                                </div>
                                <div>
                                    <div class="flex justify-between text-sm font-semibold mb-2">
                                        <span class="text-slate-700">Experience Match</span>
                                        <span class="text-slate-900">80%</span>
                                    </div>
                                    <div class="h-2 w-full bg-slate-100 rounded-full overflow-hidden">
                                        <div class="h-full bg-emerald-500 rounded-full w-[80%]"></div>
                                    </div>
                                </div>
                                <div>
                                    <div class="flex justify-between text-sm font-semibold mb-2">
                                        <span class="text-slate-700">Education Match</span>
                                        <span class="text-slate-900">90%</span>
                                    </div>
                                    <div class="h-2 w-full bg-slate-100 rounded-full overflow-hidden">
                                        <div class="h-full bg-emerald-500 rounded-full w-[90%]"></div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- Tags --}}
                        <div>
                            <h4 class="text-xs font-bold text-slate-900 mb-4 uppercase tracking-wider">Top Strengths</h4>
                            <div class="flex flex-wrap gap-2">
                                <span class="px-3 py-1.5 bg-slate-50 text-slate-600 rounded-lg text-xs font-semibold border border-slate-100">Project Management</span>
                                <span class="px-3 py-1.5 bg-slate-50 text-slate-600 rounded-lg text-xs font-semibold border border-slate-100">Data Analysis</span>
                                <span class="px-3 py-1.5 bg-slate-50 text-slate-600 rounded-lg text-xs font-semibold border border-slate-100">Problem Solving</span>
                                <span class="px-3 py-1.5 bg-slate-50 text-slate-600 rounded-lg text-xs font-semibold border border-slate-100">Leadership</span>
                            </div>
                        </div>
                    </div>

                    {{-- Tip Banner --}}
                    <div class="mt-6 bg-emerald-50 border border-emerald-100 rounded-2xl p-4 flex items-start sm:items-center gap-3">
                        <svg class="w-6 h-6 text-emerald-500 shrink-0 mt-0.5 sm:mt-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z" />
                        </svg>
                        <p class="text-sm text-emerald-800"><span class="font-semibold">Tip:</span> The more complete your CV is, the better the matches!</p>
                    </div>
                </div>
            </div>

            {{-- How It Works --}}
            <div class="py-16 border-t border-slate-200">
                <h2 class="text-3xl font-[family-name:var(--font-display)] font-bold text-center text-slate-900 mb-16">How CV Matcher Works</h2>
                
                <div class="grid grid-cols-1 md:grid-cols-4 gap-8 relative">
                    {{-- Connector lines (hidden on mobile) --}}
                    <div class="hidden md:block absolute top-12 left-[15%] right-[15%] h-0.5 bg-slate-200 border-t-2 border-dashed border-slate-200"></div>

                    {{-- Step 1 --}}
                    <div class="flex flex-col items-center text-center relative z-10">
                        <div class="w-24 h-24 bg-white rounded-full flex items-center justify-center shadow-lg border border-slate-100 mb-6 relative">
                            <div class="absolute -top-2 -left-2 w-8 h-8 bg-emerald-600 text-white rounded-full flex items-center justify-center font-bold text-sm border-4 border-white shadow-sm">1</div>
                            <svg class="w-10 h-10 text-emerald-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12" />
                            </svg>
                        </div>
                        <h3 class="font-bold text-slate-900 mb-2">Upload CV</h3>
                        <p class="text-sm text-slate-500 px-4">Upload your CV in PDF or DOCX format.</p>
                    </div>

                    {{-- Step 2 --}}
                    <div class="flex flex-col items-center text-center relative z-10">
                        <div class="w-24 h-24 bg-white rounded-full flex items-center justify-center shadow-lg border border-slate-100 mb-6 relative">
                            <div class="absolute -top-2 -left-2 w-8 h-8 bg-emerald-600 text-white rounded-full flex items-center justify-center font-bold text-sm border-4 border-white shadow-sm">2</div>
                            <svg class="w-10 h-10 text-emerald-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z" />
                            </svg>
                        </div>
                        <h3 class="font-bold text-slate-900 mb-2">AI Analyzes</h3>
                        <p class="text-sm text-slate-500 px-4">Our AI extracts your skills, experience, and strengths.</p>
                    </div>

                    {{-- Step 3 --}}
                    <div class="flex flex-col items-center text-center relative z-10">
                        <div class="w-24 h-24 bg-white rounded-full flex items-center justify-center shadow-lg border border-slate-100 mb-6 relative">
                            <div class="absolute -top-2 -left-2 w-8 h-8 bg-emerald-600 text-white rounded-full flex items-center justify-center font-bold text-sm border-4 border-white shadow-sm">3</div>
                            <svg class="w-10 h-10 text-emerald-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15.042 21.672L13.684 16.6m0 0l-2.51 2.225.569-9.47 5.227 7.917-3.286-.672zm-7.518-.267A8.25 8.25 0 1120.25 10.5M8.288 14.212A5.25 5.25 0 1117.25 10.5" />
                            </svg>
                        </div>
                        <h3 class="font-bold text-slate-900 mb-2">Match with Jobs</h3>
                        <p class="text-sm text-slate-500 px-4">We find jobs that best match your profile.</p>
                    </div>

                    {{-- Step 4 --}}
                    <div class="flex flex-col items-center text-center relative z-10">
                        <div class="w-24 h-24 bg-white rounded-full flex items-center justify-center shadow-lg border border-slate-100 mb-6 relative">
                            <div class="absolute -top-2 -left-2 w-8 h-8 bg-emerald-600 text-white rounded-full flex items-center justify-center font-bold text-sm border-4 border-white shadow-sm">4</div>
                            <svg class="w-10 h-10 text-emerald-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 18L9 11.25l4.306 4.307a11.95 11.95 0 015.814-5.519l2.74-1.22m0 0l-5.94-2.28m5.94 2.28l-2.28 5.941" />
                            </svg>
                        </div>
                        <h3 class="font-bold text-slate-900 mb-2">Get Better Results</h3>
                        <p class="text-sm text-slate-500 px-4">Save time and apply to the right opportunities.</p>
                    </div>
                </div>
            </div>

            {{-- Recent Matches --}}
            <div class="py-16">
                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-8">
                    <div>
                        <h2 class="text-2xl font-bold text-slate-900 mb-1">Your Recent Matches</h2>
                        <p class="text-sm text-slate-500">Jobs that might be a great fit for you.</p>
                    </div>
                    <button class="px-5 py-2.5 bg-white border border-slate-200 hover:border-slate-300 text-slate-700 font-semibold rounded-xl text-sm transition-colors flex items-center gap-2 self-start sm:self-auto shadow-sm">
                        View All Matches
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7" />
                        </svg>
                    </button>
                </div>

                <div class="space-y-4">
                    {{-- Job Card 1 --}}
                    <div class="bg-white rounded-2xl p-6 shadow-sm border border-slate-200 flex flex-col lg:flex-row gap-6 hover:shadow-md transition-shadow">
                        <div class="flex-1 flex gap-4">
                            <div class="w-16 h-16 bg-slate-900 rounded-xl flex items-center justify-center text-white font-bold text-2xl shrink-0">
                                S
                            </div>
                            <div>
                                <h3 class="text-lg font-bold text-slate-900 mb-1">Product Manager</h3>
                                <div class="flex items-center flex-wrap gap-2 text-sm text-slate-600 mb-3">
                                    <span class="font-semibold flex items-center gap-1 text-slate-900">
                                        Shopify
                                        <svg class="w-4 h-4 text-emerald-500" viewBox="0 0 20 20" fill="currentColor">
                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                                        </svg>
                                    </span>
                                    <span class="text-slate-300">•</span>
                                    <span class="flex items-center gap-1">
                                        <svg class="w-4 h-4 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" /><path stroke-linecap="round" stroke-linejoin="round" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" /></svg>
                                        Toronto, Canada
                                    </span>
                                    <span class="text-slate-300">•</span>
                                    <span class="flex items-center gap-1">
                                        <svg class="w-4 h-4 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" /></svg>
                                        Full-time
                                    </span>
                                </div>
                                <p class="text-sm text-slate-600"><span class="font-semibold text-slate-900">AI Match Insight:</span> Your experience in product strategy and team collaboration aligns well with this role.</p>
                            </div>
                        </div>
                        
                        <div class="lg:w-72 shrink-0 flex flex-col justify-between border-t lg:border-t-0 lg:border-l border-slate-100 pt-4 lg:pt-0 lg:pl-6 relative">
                            <button class="absolute top-0 right-0 text-slate-400 hover:text-emerald-600 transition-colors">
                                <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M5 5a2 2 0 012-2h10a2 2 0 012 2v16l-7-3.5L5 21V5z" />
                                </svg>
                            </button>
                            <div class="mb-4 pr-8">
                                <div class="flex items-center gap-2 text-emerald-600 font-bold text-sm mb-2">
                                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                                    </svg>
                                    High Potential Match
                                </div>
                                <ul class="space-y-1 text-xs text-slate-500">
                                    <li class="flex items-center gap-1.5"><svg class="w-3.5 h-3.5 text-emerald-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" /></svg> Strong match in skills</li>
                                    <li class="flex items-center gap-1.5"><svg class="w-3.5 h-3.5 text-emerald-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" /></svg> Relevant experience</li>
                                    <li class="flex items-center gap-1.5"><svg class="w-3.5 h-3.5 text-emerald-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" /></svg> Great culture fit</li>
                                </ul>
                            </div>
                            <button class="w-full py-2 bg-white border border-emerald-600 text-emerald-600 hover:bg-emerald-50 font-semibold rounded-lg text-sm transition-colors text-center">
                                View Job
                            </button>
                        </div>
                    </div>

                    {{-- Job Card 2 --}}
                    <div class="bg-white rounded-2xl p-6 shadow-sm border border-slate-200 flex flex-col lg:flex-row gap-6 hover:shadow-md transition-shadow">
                        <div class="flex-1 flex gap-4">
                            <div class="w-16 h-16 bg-white border border-slate-100 rounded-xl flex items-center justify-center shrink-0">
                                {{-- Fake Google Logo --}}
                                <svg class="w-8 h-8" viewBox="0 0 24 24">
                                    <path fill="#4285F4" d="M23.745 12.27c0-.825-.075-1.62-.21-2.385H12.24v4.5h6.465a5.535 5.535 0 01-2.4 3.63v3h3.87a11.64 11.64 0 003.57-8.745z" />
                                    <path fill="#34A853" d="M12.24 24c3.24 0 5.955-1.08 7.935-2.925l-3.87-3c-1.08.72-2.46 1.155-4.065 1.155-3.135 0-5.79-2.115-6.735-4.965h-4.02v3.12A11.97 11.97 0 0012.24 24z" />
                                    <path fill="#FBBC05" d="M5.505 14.265a7.14 7.14 0 010-4.53v-3.12h-4.02a11.985 11.985 0 000 10.77l4.02-3.12z" />
                                    <path fill="#EA4335" d="M12.24 4.77c1.77 0 3.36.615 4.605 1.8l3.465-3.465C18.18 1.185 15.465 0 12.24 0 7.425 0 3.24 2.805 1.485 6.945l4.02 3.12c.945-2.85 3.6-4.965 6.735-4.965z" />
                                </svg>
                            </div>
                            <div>
                                <h3 class="text-lg font-bold text-slate-900 mb-1">Data Analyst</h3>
                                <div class="flex items-center flex-wrap gap-2 text-sm text-slate-600 mb-3">
                                    <span class="font-semibold flex items-center gap-1 text-slate-900">
                                        Google
                                        <svg class="w-4 h-4 text-emerald-500" viewBox="0 0 20 20" fill="currentColor">
                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                                        </svg>
                                    </span>
                                    <span class="text-slate-300">•</span>
                                    <span class="flex items-center gap-1">
                                        <svg class="w-4 h-4 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" /><path stroke-linecap="round" stroke-linejoin="round" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" /></svg>
                                        Mountain View, USA
                                    </span>
                                    <span class="text-slate-300">•</span>
                                    <span class="flex items-center gap-1">
                                        <svg class="w-4 h-4 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" /></svg>
                                        Full-time
                                    </span>
                                </div>
                                <p class="text-sm text-slate-600"><span class="font-semibold text-slate-900">AI Match Insight:</span> Your data analysis and reporting skills are a great fit for this role.</p>
                            </div>
                        </div>
                        
                        <div class="lg:w-72 shrink-0 flex flex-col justify-between border-t lg:border-t-0 lg:border-l border-slate-100 pt-4 lg:pt-0 lg:pl-6 relative">
                            <button class="absolute top-0 right-0 text-slate-400 hover:text-emerald-600 transition-colors">
                                <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M5 5a2 2 0 012-2h10a2 2 0 012 2v16l-7-3.5L5 21V5z" />
                                </svg>
                            </button>
                            <div class="mb-4 pr-8">
                                <div class="flex items-center gap-2 text-emerald-600 font-bold text-sm mb-2">
                                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                                    </svg>
                                    High Potential Match
                                </div>
                                <ul class="space-y-1 text-xs text-slate-500">
                                    <li class="flex items-center gap-1.5"><svg class="w-3.5 h-3.5 text-emerald-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" /></svg> Strong match in skills</li>
                                    <li class="flex items-center gap-1.5"><svg class="w-3.5 h-3.5 text-emerald-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" /></svg> Relevant experience</li>
                                    <li class="flex items-center gap-1.5"><svg class="w-3.5 h-3.5 text-emerald-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" /></svg> Great culture fit</li>
                                </ul>
                            </div>
                            <button class="w-full py-2 bg-white border border-emerald-600 text-emerald-600 hover:bg-emerald-50 font-semibold rounded-lg text-sm transition-colors text-center">
                                View Job
                            </button>
                        </div>
                    </div>

                    {{-- Job Card 3 --}}
                    <div class="bg-white rounded-2xl p-6 shadow-sm border border-slate-200 flex flex-col lg:flex-row gap-6 hover:shadow-md transition-shadow">
                        <div class="flex-1 flex gap-4">
                            <div class="w-16 h-16 bg-white border border-slate-100 rounded-xl flex flex-wrap items-center justify-center shrink-0 p-3">
                                {{-- Fake Microsoft Logo --}}
                                <div class="w-full h-full grid grid-cols-2 gap-[2px]">
                                    <div class="bg-[#F25022]"></div>
                                    <div class="bg-[#7FBA00]"></div>
                                    <div class="bg-[#00A4EF]"></div>
                                    <div class="bg-[#FFB900]"></div>
                                </div>
                            </div>
                            <div>
                                <h3 class="text-lg font-bold text-slate-900 mb-1">Software Engineer</h3>
                                <div class="flex items-center flex-wrap gap-2 text-sm text-slate-600 mb-3">
                                    <span class="font-semibold flex items-center gap-1 text-slate-900">
                                        Microsoft
                                        <svg class="w-4 h-4 text-emerald-500" viewBox="0 0 20 20" fill="currentColor">
                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                                        </svg>
                                    </span>
                                    <span class="text-slate-300">•</span>
                                    <span class="flex items-center gap-1">
                                        <svg class="w-4 h-4 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" /><path stroke-linecap="round" stroke-linejoin="round" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" /></svg>
                                        Redmond, USA
                                    </span>
                                    <span class="text-slate-300">•</span>
                                    <span class="flex items-center gap-1">
                                        <svg class="w-4 h-4 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" /></svg>
                                        Full-time
                                    </span>
                                </div>
                                <p class="text-sm text-slate-600"><span class="font-semibold text-slate-900">AI Match Insight:</span> Your engineering experience and technical skills match well with this opportunity.</p>
                            </div>
                        </div>
                        
                        <div class="lg:w-72 shrink-0 flex flex-col justify-between border-t lg:border-t-0 lg:border-l border-slate-100 pt-4 lg:pt-0 lg:pl-6 relative">
                            <button class="absolute top-0 right-0 text-slate-400 hover:text-emerald-600 transition-colors">
                                <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M5 5a2 2 0 012-2h10a2 2 0 012 2v16l-7-3.5L5 21V5z" />
                                </svg>
                            </button>
                            <div class="mb-4 pr-8">
                                <div class="flex items-center gap-2 text-amber-500 font-bold text-sm mb-2">
                                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                    Good Potential Match
                                </div>
                                <ul class="space-y-1 text-xs text-slate-500">
                                    <li class="flex items-center gap-1.5"><svg class="w-3.5 h-3.5 text-emerald-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" /></svg> Good skill alignment</li>
                                    <li class="flex items-center gap-1.5"><svg class="w-3.5 h-3.5 text-emerald-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" /></svg> Some experience match</li>
                                    <li class="flex items-center gap-1.5"><svg class="w-3.5 h-3.5 text-emerald-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" /></svg> Possible culture fit</li>
                                </ul>
                            </div>
                            <button class="w-full py-2 bg-white border border-emerald-600 text-emerald-600 hover:bg-emerald-50 font-semibold rounded-lg text-sm transition-colors text-center">
                                View Job
                            </button>
                        </div>
                    </div>
                </div>

                <div class="mt-8 flex justify-center">
                    <button class="px-6 py-2.5 bg-white border border-emerald-600 text-emerald-600 hover:bg-emerald-50 font-semibold rounded-xl text-sm transition-colors shadow-sm">
                        Explore More Jobs
                    </button>
                </div>
            </div>

            {{-- Bottom Banner CTA --}}
            <div class="mt-12 bg-gradient-to-r from-emerald-50 to-teal-50 rounded-3xl p-8 lg:p-12 border border-emerald-100 flex flex-col md:flex-row items-center justify-between gap-8 relative overflow-hidden">
                <div class="absolute top-0 right-0 w-64 h-64 bg-emerald-100 rounded-full blur-3xl opacity-50 pointer-events-none"></div>
                
                <div class="flex items-center gap-8 relative z-10 w-full md:w-auto">
                    <div class="hidden sm:block relative">
                        <div class="w-32 h-24 bg-white rounded-xl shadow-md border border-slate-100 p-2 transform -rotate-6 z-10 relative">
                            <div class="flex gap-2 mb-2">
                                <div class="w-4 h-4 bg-emerald-100 rounded-full"></div>
                                <div class="w-16 h-2 bg-slate-100 rounded-full mt-1"></div>
                            </div>
                            <div class="space-y-1">
                                <div class="w-full h-1.5 bg-slate-100 rounded-full"></div>
                                <div class="w-5/6 h-1.5 bg-slate-100 rounded-full"></div>
                            </div>
                            <div class="absolute -bottom-3 -right-3 w-8 h-8 bg-emerald-500 rounded-full flex items-center justify-center text-white shadow-sm border-2 border-white">
                                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                                </svg>
                            </div>
                        </div>
                        <div class="w-32 h-24 bg-white rounded-xl shadow-sm border border-slate-100 p-2 absolute -right-6 top-2 rotate-6 z-0">
                            <div class="flex gap-2 mb-2">
                                <div class="w-4 h-4 bg-slate-100 rounded-full"></div>
                                <div class="w-12 h-2 bg-slate-100 rounded-full mt-1"></div>
                            </div>
                            <div class="space-y-1">
                                <div class="w-full h-1.5 bg-slate-100 rounded-full"></div>
                                <div class="w-3/4 h-1.5 bg-slate-100 rounded-full"></div>
                            </div>
                        </div>
                    </div>
                    
                    <div>
                        <h2 class="text-2xl font-bold text-slate-900 mb-2">Get better matches, apply smarter.</h2>
                        <p class="text-slate-600">Keep your CV updated and increase your chances<br class="hidden md:block"> of getting noticed by top companies.</p>
                    </div>
                </div>

                <div class="relative z-10 shrink-0 w-full md:w-auto flex flex-col items-center md:items-end gap-2">
                    <button class="w-full md:w-auto px-8 py-4 bg-emerald-600 hover:bg-emerald-700 text-white font-bold rounded-xl transition-all shadow-md hover:shadow-lg active:scale-[0.98] text-center">
                        Upload / Update CV
                    </button>
                    <div class="flex items-center gap-1.5 text-xs text-slate-500 font-medium">
                        <svg class="w-3.5 h-3.5 text-emerald-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                        </svg>
                        Your data is secure and private.
                    </div>
                </div>
            </div>

        </div>
    </div>
</x-layout>
