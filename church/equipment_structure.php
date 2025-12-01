<?php
include('includes/dbconnection.php');

echo "<!DOCTYPE html>
<html>
<head>
    <title>Equipment Table Structure</title>
    <script src='https://cdn.tailwindcss.com'></script>
</head>
<body class='bg-gray-100'>
    <div class='max-w-4xl mx-auto p-6'>
        <h1 class='text-3xl font-bold text-center text-gray-800 mb-8'>Equipment Table Structure</h1>";

try {
    // Check structure of church_equipment table
    $stmt = $dbh->prepare("DESCRIBE church_equipment");
    $stmt->execute();
    $columns = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    echo "<div class='bg-white shadow rounded-lg p-6 mb-6'>
            <h2 class='text-xl font-bold text-gray-800 mb-4'>church_equipment Table Structure</h2>
            <div class='overflow-x-auto'>
                <table class='min-w-full divide-y divide-gray-200'>
                    <thead class='bg-gray-50'>
                        <tr>
                            <th class='px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider'>Field</th>
                            <th class='px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider'>Type</th>
                            <th class='px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider'>Null</th>
                            <th class='px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider'>Key</th>
                            <th class='px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider'>Default</th>
                            <th class='px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider'>Extra</th>
                        </tr>
                    </thead>
                    <tbody class='bg-white divide-y divide-gray-200'>";
    
    foreach ($columns as $column) {
        echo "<tr>
                <td class='px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900'>" . $column['Field'] . "</td>
                <td class='px-6 py-4 whitespace-nowrap text-sm text-gray-500'>" . $column['Type'] . "</td>
                <td class='px-6 py-4 whitespace-nowrap text-sm text-gray-500'>" . $column['Null'] . "</td>
                <td class='px-6 py-4 whitespace-nowrap text-sm text-gray-500'>" . $column['Key'] . "</td>
                <td class='px-6 py-4 whitespace-nowrap text-sm text-gray-500'>" . $column['Default'] . "</td>
                <td class='px-6 py-4 whitespace-nowrap text-sm text-gray-500'>" . $column['Extra'] . "</td>
              </tr>";
    }
    
    echo "</tbody>
          </table>
        </div>
      </div>";
    
} catch (Exception $e) {
    echo "<div class='bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-6'>
            <strong>Error:</strong> " . $e->getMessage() . "
          </div>";
}

echo "</div>
</body>
</html>";
?>