<nav aria-label="breadcrumb" class="app-breadcrumb">
    <ol class="breadcrumb mb-0">
        <li class="breadcrumb-item">
            <a href="{{ route('/') }}" class="breadcrumb-link">
                <i class="breadcrumb-link bi bi-house-door-fill"></i>
            </a>
        </li>
        
        @php
            $breadcrumbs = [
                '/ce/sites' => 'Sites',
                '/ce/users' => 'Users',
                '/ce/paper-types' => 'Paper Types',
                '/ce/vendors' => 'Vendors',
                '/ce/items' => 'Items',
                '/ce/items/add' => 'Add Item',
                '/ce/items/edit' => 'Edit Item',
                '/ce/paper-board-price' => 'Paper Board Price',
                '/ce/u' => 'Profile',
            ];
            
            $currentRoute = request()->path();
            $routeParts = explode('/', trim($currentRoute, '/'));
            $breadcrumbPath = '';
            $lastMatchedPath = '';
            $breadcrumbItems = [];
            
            foreach ($routeParts as $index => $part) {
                $breadcrumbPath .= '/' . $part;
                
                // Skip 'ce' prefix in breadcrumb display
                if ($part === 'ce') {
                    continue;
                }
                
                // Don't show user IDs in breadcrumb
                if (preg_match('/^[A-Za-z0-9]+$/', $part) && isset($routeParts[$index - 1]) && $routeParts[$index - 1] === 'u') {
                    continue;
                }
                
                // Find matching breadcrumb label
                $label = null;
                foreach ($breadcrumbs as $path => $name) {
                    if (strpos($breadcrumbPath, $path) === 0 && strlen($path) > strlen($lastMatchedPath)) {
                        $label = $name;
                        $lastMatchedPath = $path;
                    }
                }
                
                if ($label) {
                    $breadcrumbItems[] = [
                        'label' => $label,
                        'path' => $breadcrumbPath
                    ];
                }
            }
        @endphp
        
        @foreach($breadcrumbItems as $index => $item)
            <li class="breadcrumb-separator">
                <i class="bi bi-chevron-right"></i>
            </li>
            @php
                // Check if this is the active/current page
                $isActive = false;
                if ($item['label'] === 'Profile') {
                    // For profile pages, check if current route starts with /ce/u
                    $isActive = true;
                } else {
                    // For other pages, match the exact path
                    $isActive = $item['path'] === '/' . $currentRoute;
                }
            @endphp
            @if($isActive)
                <li class="breadcrumb-item active">
                    <span class="breadcrumb-text">{{ $item['label'] }}</span>
                </li>
            @else
                <li class="breadcrumb-item">
                    <a href="{{ url($item['path']) }}" class="breadcrumb-link">{{ $item['label'] }}</a>
                </li>
            @endif
        @endforeach
    </ol>
</nav>