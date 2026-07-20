# Dashboard Cards Usage Guide

## Installation

Include the CSS file in your Blade template:

### Option 1: Using @push (if your layout supports it)

```blade
@push('styles')
    <link href="{{ asset('css/dashboard-cards.css') }}" rel="stylesheet">
@endpush
```

### Option 2: Direct inclusion in head

```blade
<link href="{{ asset('css/dashboard-cards.css') }}" rel="stylesheet">
```

## Basic Card Structure

```blade
<div class="col-sm-6 col-md-3">
    <div class="stats-card">
        <div class="stats-card-header">
            <h6 class="stats-card-title">Card Title</h6>
            <div class="stats-card-icon">
                <i class="fas fa-icon-name"></i>
            </div>
        </div>
        <div class="stats-card-body">
            <h2 class="stats-card-value">1,234</h2>
        </div>
        <div class="stats-card-footer">
            <a href="#" class="stats-card-link">
                View All <i class="fas fa-arrow-right"></i>
            </a>
        </div>
    </div>
</div>
```

## Card Types (Color Variants)

Available classes to add to `.stats-card`:

- Default (blue) - no additional class
- `.warning` - Orange/yellow gradient
- `.success` - Green/cyan gradient
- `.info` - Cyan/turquoise gradient
- `.danger` - Pink/yellow gradient

Example:

```blade
<div class="stats-card warning">
    <!-- Card content -->
</div>
```

## Components

### 1. Card Header

```blade
<div class="stats-card-header">
    <h6 class="stats-card-title">UPPERCASE TITLE</h6>
    <div class="stats-card-icon">
        <i class="fas fa-users"></i>
    </div>
</div>
```

### 2. Card Body

```blade
<div class="stats-card-body">
    <h2 class="stats-card-value">{{ number_format($count, 0) }}</h2>
    <p class="stats-card-label">Optional subtitle</p>
</div>
```

### 3. Card Footer (with link)

```blade
<div class="stats-card-footer">
    <a href="{{ url('path') }}" class="stats-card-link">
        View Details <i class="fas fa-arrow-right"></i>
    </a>
</div>
```

### 4. Card Footer (with badge)

```blade
<div class="stats-card-footer">
    <span class="stats-badge">
        <i class="fas fa-arrow-up"></i> Active
    </span>
</div>
```

Badge variants:

- `.stats-badge` - Green (default)
- `.stats-badge.warning` - Orange
- `.stats-badge.danger` - Red
- `.stats-badge.info` - Blue

## Full Example

```blade
<div class="row stats-row">
    <!-- Card 1 -->
    <div class="col-sm-6 col-md-3 mb-3">
        <div class="stats-card">
            <div class="stats-card-header">
                <h6 class="stats-card-title">Total Users</h6>
                <div class="stats-card-icon">
                    <i class="fas fa-users"></i>
                </div>
            </div>
            <div class="stats-card-body">
                <h2 class="stats-card-value">{{ number_format(App\User::count(), 0) }}</h2>
            </div>
            <div class="stats-card-footer">
                <a href="{{ route('users.index') }}" class="stats-card-link">
                    View All <i class="fas fa-arrow-right"></i>
                </a>
            </div>
        </div>
    </div>

    <!-- Card 2 - Warning variant -->
    <div class="col-sm-6 col-md-3 mb-3">
        <div class="stats-card warning">
            <div class="stats-card-header">
                <h6 class="stats-card-title">Pending Tasks</h6>
                <div class="stats-card-icon">
                    <i class="fas fa-clock"></i>
                </div>
            </div>
            <div class="stats-card-body">
                <h2 class="stats-card-value">25</h2>
            </div>
            <div class="stats-card-footer">
                <a href="#" class="stats-card-link">
                    View Tasks <i class="fas fa-arrow-right"></i>
                </a>
            </div>
        </div>
    </div>

    <!-- Card 3 - Success variant with badge -->
    <div class="col-sm-6 col-md-3 mb-3">
        <div class="stats-card success">
            <div class="stats-card-header">
                <h6 class="stats-card-title">Active Projects</h6>
                <div class="stats-card-icon">
                    <i class="fas fa-project-diagram"></i>
                </div>
            </div>
            <div class="stats-card-body">
                <h2 class="stats-card-value">42</h2>
            </div>
            <div class="stats-card-footer">
                <span class="stats-badge">
                    <i class="fas fa-check"></i> All Active
                </span>
            </div>
        </div>
    </div>

    <!-- Card 4 - Info variant -->
    <div class="col-sm-6 col-md-3 mb-3">
        <div class="stats-card info">
            <div class="stats-card-header">
                <h6 class="stats-card-title">Revenue</h6>
                <div class="stats-card-icon">
                    <i class="fas fa-dollar-sign"></i>
                </div>
            </div>
            <div class="stats-card-body">
                <h2 class="stats-card-value">$12,450</h2>
            </div>
            <div class="stats-card-footer">
                <span class="stats-badge info">
                    <i class="fas fa-arrow-up"></i> +12%
                </span>
            </div>
        </div>
    </div>
</div>
```

## Optional Features

### Progress Bar

Add a progress bar at the bottom of the card:

```blade
<div class="stats-card-progress">
    <div class="stats-card-progress-bar" style="width: 75%;"></div>
</div>
```

### Loading State

Add the `.loading` class to show a loading spinner:

```blade
<div class="stats-card loading">
    <!-- Card content -->
</div>
```

## Recommended Icons (Font Awesome)

- Users: `fa-users`, `fa-user`, `fa-user-friends`
- Statistics: `fa-chart-line`, `fa-chart-bar`, `fa-analytics`
- Tasks: `fa-tasks`, `fa-clipboard-list`, `fa-check-square`
- Time: `fa-clock`, `fa-hourglass-half`, `fa-calendar`
- Money: `fa-dollar-sign`, `fa-coins`, `fa-wallet`
- Alerts: `fa-exclamation-circle`, `fa-bell`, `fa-info-circle`
- Success: `fa-check-circle`, `fa-thumbs-up`
- Buildings: `fa-building`, `fa-store`, `fa-warehouse`
- Documents: `fa-file-alt`, `fa-folder`, `fa-clipboard`
- Actions: `fa-sync-alt`, `fa-download`, `fa-upload`

## Responsive Behavior

- Desktop (≥992px): 4 cards per row (col-md-3)
- Tablet (≥768px): 2 cards per row (col-sm-6)
- Mobile (<768px): 1 card per row (stacks vertically)

## Best Practices

1. Keep titles short and uppercase
2. Use large, bold numbers for values
3. Choose icons that represent the metric
4. Use consistent color coding:

   - Default/Blue: General information
   - Warning/Orange: Attention needed
   - Success/Green: Positive metrics
   - Info/Cyan: Informational stats
   - Danger/Red: Critical alerts

5. Add hover states are built-in (cards lift on hover)
6. Use number_format() for large numbers: `{{ number_format($value, 0) }}`
