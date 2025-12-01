<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Icon Test</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="bg-gray-100 p-8">
  <h1 class="text-3xl font-bold mb-6">Icon Test</h1>
  
  <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-4">
    <!-- Members -->
    <div class="flex flex-col items-center p-4 bg-blue-50 rounded-lg">
      <div class="p-3 bg-blue-100 rounded-full text-blue-500">
        <i class="fas fa-users text-xl"></i>
      </div>
      <span class="mt-2 text-sm font-medium text-gray-700">Members</span>
    </div>

    <!-- Events -->
    <div class="flex flex-col items-center p-4 bg-purple-50 rounded-lg">
      <div class="p-3 bg-purple-100 rounded-full text-purple-500">
        <i class="fas fa-calendar-alt text-xl"></i>
      </div>
      <span class="mt-2 text-sm font-medium text-gray-700">Events</span>
    </div>

    <!-- Donations -->
    <div class="flex flex-col items-center p-4 bg-green-50 rounded-lg">
      <div class="p-3 bg-green-100 rounded-full text-green-500">
        <i class="fas fa-donate text-xl"></i>
      </div>
      <span class="mt-2 text-sm font-medium text-gray-700">Donations</span>
    </div>

    <!-- Resources -->
    <div class="flex flex-col items-center p-4 bg-yellow-50 rounded-lg">
      <div class="p-3 bg-yellow-100 rounded-full text-yellow-500">
        <i class="fas fa-boxes text-xl"></i>
      </div>
      <span class="mt-2 text-sm font-medium text-gray-700">Resources</span>
    </div>

    <!-- Worship Teams -->
    <div class="flex flex-col items-center p-4 bg-pink-50 rounded-lg">
      <div class="p-3 bg-pink-100 rounded-full text-pink-500">
        <i class="fas fa-music text-xl"></i>
      </div>
      <span class="mt-2 text-sm font-medium text-gray-700">Worship Teams</span>
    </div>

    <!-- Reports -->
    <div class="flex flex-col items-center p-4 bg-indigo-50 rounded-lg">
      <div class="p-3 bg-indigo-100 rounded-full text-indigo-500">
        <i class="fas fa-chart-bar text-xl"></i>
      </div>
      <span class="mt-2 text-sm font-medium text-gray-700">Reports</span>
    </div>
  </div>
  
  <div class="mt-8 p-4 bg-white rounded-lg shadow-md">
    <h2 class="text-xl font-bold mb-4">Direct Icon Test</h2>
    <p><i class="fas fa-users"></i> Users Icon</p>
    <p><i class="fas fa-calendar-alt"></i> Calendar Icon</p>
    <p><i class="fas fa-donate"></i> Donate Icon</p>
    <p><i class="fas fa-boxes"></i> Boxes Icon</p>
    <p><i class="fas fa-music"></i> Music Icon</p>
    <p><i class="fas fa-chart-bar"></i> Chart Icon</p>
  </div>
</body>
</html>