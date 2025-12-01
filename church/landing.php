<?php
session_start();
error_reporting(0);
// No authentication required for landing page
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Church Management System</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: '#3b82f6',
                        secondary: '#10b981',
                        accent: '#8b5cf6',
                        dark: '#1e293b',
                        light: '#f8fafc'
                    }
                }
            }
        }
    </script>
</head>
<body class="bg-gray-100">
    <!-- Navigation -->
    <nav class="bg-white shadow-lg">
        <div class="max-w-7xl mx-auto px-4">
            <div class="flex justify-between">
                <div class="flex space-x-7">
                    <div>
                        <a href="landing.php" class="flex items-center py-4">
                            <i class="fas fa-church text-primary text-2xl mr-2"></i>
                            <span class="font-semibold text-gray-800 text-lg">Church Management System</span>
                        </a>
                    </div>
                </div>
                <div class="hidden md:flex items-center space-x-3">
                    <a href="index.php" class="bg-primary hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                        <i class="fas fa-sign-in-alt mr-2"></i>Login
                    </a>
                </div>
                <div class="md:hidden flex items-center">
                    <button id="mobile-menu-button" class="outline-none">
                        <svg class="w-6 h-6 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                        </svg>
                    </button>
                </div>
            </div>
        </div>
        <!-- Mobile menu -->
        <div id="mobile-menu" class="hidden md:hidden">
            <a href="index.php" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Login</a>
        </div>
    </nav>

    <!-- Hero Section -->
    <div class="bg-gradient-to-r from-blue-500 to-purple-600 text-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-24">
            <div class="text-center">
                <h1 class="text-4xl md:text-6xl font-bold mb-6">Complete Church Management Solution</h1>
                <p class="text-xl md:text-2xl mb-8 max-w-3xl mx-auto">Manage your multi-branch church operations with ease and efficiency</p>
                <div class="flex flex-col sm:flex-row justify-center gap-4">
                    <a href="index.php" class="bg-white text-primary hover:bg-gray-100 font-bold py-3 px-6 rounded-lg text-lg">
                        <i class="fas fa-sign-in-alt mr-2"></i>Get Started
                    </a>
                    <a href="#features" class="bg-transparent border-2 border-white hover:bg-white hover:text-primary font-bold py-3 px-6 rounded-lg text-lg">
                        <i class="fas fa-info-circle mr-2"></i>Learn More
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Features Section -->
    <div id="features" class="py-16 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16">
                <h2 class="text-3xl md:text-4xl font-bold text-gray-900 mb-4">Powerful Features</h2>
                <p class="text-xl text-gray-600 max-w-3xl mx-auto">Everything you need to manage your church operations efficiently</p>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                <!-- Member Management -->
                <div class="bg-gray-50 rounded-lg p-6 shadow-md hover:shadow-lg transition-shadow">
                    <div class="text-primary text-3xl mb-4">
                        <i class="fas fa-users"></i>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-2">Member Management</h3>
                    <p class="text-gray-600">Keep track of all members, their contact details, attendance, and participation in events across all branches.</p>
                </div>
                
                <!-- Event Management -->
                <div class="bg-gray-50 rounded-lg p-6 shadow-md hover:shadow-lg transition-shadow">
                    <div class="text-primary text-3xl mb-4">
                        <i class="fas fa-calendar-alt"></i>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-2">Event Management</h3>
                    <p class="text-gray-600">Organize and schedule events, track RSVPs, and communicate with attendees across all church branches.</p>
                </div>
                
                <!-- Donation Tracking -->
                <div class="bg-gray-50 rounded-lg p-6 shadow-md hover:shadow-lg transition-shadow">
                    <div class="text-primary text-3xl mb-4">
                        <i class="fas fa-hand-holding-usd"></i>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-2">Donation Tracking</h3>
                    <p class="text-gray-600">Manage donations, generate receipts, and provide detailed financial reports for transparent accounting.</p>
                </div>
                
                <!-- Communication Tools -->
                <div class="bg-gray-50 rounded-lg p-6 shadow-md hover:shadow-lg transition-shadow">
                    <div class="text-primary text-3xl mb-4">
                        <i class="fas fa-comments"></i>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-2">Communication Tools</h3>
                    <p class="text-gray-600">Send emails or messages to members, groups, or the entire congregation with our integrated messaging system.</p>
                </div>
                
                <!-- Resource Management -->
                <div class="bg-gray-50 rounded-lg p-6 shadow-md hover:shadow-lg transition-shadow">
                    <div class="text-primary text-3xl mb-4">
                        <i class="fas fa-boxes"></i>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-2">Resource Management</h3>
                    <p class="text-gray-600">Keep track of equipment, rooms, and other resources used by the church with maintenance scheduling.</p>
                </div>
                
                <!-- Multi-Branch Support -->
                <div class="bg-gray-50 rounded-lg p-6 shadow-md hover:shadow-lg transition-shadow">
                    <div class="text-primary text-3xl mb-4">
                        <i class="fas fa-sitemap"></i>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-2">Multi-Branch Support</h3>
                    <p class="text-gray-600">Integrate multiple branches with a central headquarters for unified data management and reporting.</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Benefits Section -->
    <div class="py-16 bg-gray-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16">
                <h2 class="text-3xl md:text-4xl font-bold text-gray-900 mb-4">Why Choose Our System?</h2>
                <p class="text-xl text-gray-600 max-w-3xl mx-auto">Designed specifically for churches with multiple branches</p>
            </div>
            
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 items-center">
                <div>
                    <img src="https://images.unsplash.com/photo-1505985000220-f4c7d8b3a3c0?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=1200&q=80" alt="Church Community" class="rounded-lg shadow-lg">
                </div>
                <div>
                    <h3 class="text-2xl font-bold text-gray-900 mb-6">Centralized Management with Branch Autonomy</h3>
                    <ul class="space-y-4">
                        <li class="flex items-start">
                            <div class="flex-shrink-0 h-6 w-6 text-green-500">
                                <i class="fas fa-check-circle"></i>
                            </div>
                            <p class="ml-2 text-gray-600">Real-time data synchronization across all branches</p>
                        </li>
                        <li class="flex items-start">
                            <div class="flex-shrink-0 h-6 w-6 text-green-500">
                                <i class="fas fa-check-circle"></i>
                            </div>
                            <p class="ml-2 text-gray-600">Role-based access control for admins and super admins</p>
                        </li>
                        <li class="flex items-start">
                            <div class="flex-shrink-0 h-6 w-6 text-green-500">
                                <i class="fas fa-check-circle"></i>
                            </div>
                            <p class="ml-2 text-gray-600">Comprehensive reporting and analytics dashboard</p>
                        </li>
                        <li class="flex items-start">
                            <div class="flex-shrink-0 h-6 w-6 text-green-500">
                                <i class="fas fa-check-circle"></i>
                            </div>
                            <p class="ml-2 text-gray-600">Secure and scalable architecture</p>
                        </li>
                        <li class="flex items-start">
                            <div class="flex-shrink-0 h-6 w-6 text-green-500">
                                <i class="fas fa-check-circle"></i>
                            </div>
                            <p class="ml-2 text-gray-600">Easy-to-use interface with modern design</p>
                        </li>
                    </ul>
                    <div class="mt-8">
                        <a href="index.php" class="bg-primary hover:bg-blue-700 text-white font-bold py-3 px-6 rounded-lg inline-flex items-center">
                            <i class="fas fa-rocket mr-2"></i>Start Managing Your Church Today
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Footer -->
    <footer class="bg-gray-900 text-white py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
                <div>
                    <h3 class="text-lg font-bold mb-4">Church Management System</h3>
                    <p class="text-gray-400">A comprehensive solution for managing multi-branch church operations efficiently.</p>
                </div>
                <div>
                    <h4 class="text-lg font-bold mb-4">Features</h4>
                    <ul class="space-y-2 text-gray-400">
                        <li>Member Management</li>
                        <li>Event Planning</li>
                        <li>Donation Tracking</li>
                        <li>Resource Management</li>
                    </ul>
                </div>
                <div>
                    <h4 class="text-lg font-bold mb-4">Support</h4>
                    <ul class="space-y-2 text-gray-400">
                        <li>Documentation</li>
                        <li>Training</li>
                        <li>Technical Support</li>
                        <li>Community Forum</li>
                    </ul>
                </div>
                <div>
                    <h4 class="text-lg font-bold mb-4">Contact</h4>
                    <ul class="space-y-2 text-gray-400">
                        <li><i class="fas fa-envelope mr-2"></i> support@churchmanagement.org</li>
                        <li><i class="fas fa-phone mr-2"></i> +1 (555) 123-4567</li>
                    </ul>
                </div>
            </div>
            <div class="border-t border-gray-800 mt-8 pt-8 text-center text-gray-400">
                <p>&copy; 2023 Church Management System. All rights reserved.</p>
            </div>
        </div>
    </footer>

    <script>
        // Mobile menu toggle
        document.getElementById('mobile-menu-button').addEventListener('click', function() {
            var menu = document.getElementById('mobile-menu');
            menu.classList.toggle('hidden');
        });
    </script>
</body>
</html>