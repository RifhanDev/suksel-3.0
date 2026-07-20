# Modern Action Buttons - Global Component Guide

## Overview

Modern, beautiful gradient action buttons with smooth animations that can be used anywhere in your application.

## 📦 Already Installed

The CSS is automatically loaded in `layouts/modern.blade.php`:

```html
<link href="{{ asset('css/modern-actions.css') }}" rel="stylesheet" />
```

## 🎨 Available Button Colors

| Class                  | Color              | Common Use       |
| ---------------------- | ------------------ | ---------------- |
| `btn-action-primary`   | Purple Gradient    | Edit/Update      |
| `btn-action-success`   | Cyan/Teal Gradient | View/Show        |
| `btn-action-info`      | Blue Gradient      | Publish/Activate |
| `btn-action-warning`   | Orange Gradient    | Unpublish/Warn   |
| `btn-action-danger`    | Red Gradient       | Delete/Remove    |
| `btn-action-secondary` | Gray Gradient      | Cancel/Neutral   |

## 📝 Basic Usage

### Single Button

```html
<a href="/edit/1" class="btn btn-sm btn-action btn-action-primary" title="Edit">
  <svg
    xmlns="http://www.w3.org/2000/svg"
    class="icon icon-sm"
    width="16"
    height="16"
    viewBox="0 0 24 24"
    stroke-width="2"
    stroke="currentColor"
    fill="none"
  >
    <path d="M7 7h-1a2 2 0 0 0 -2 2v9a2 2 0 0 0 2 2h9a2 2 0 0 0 2 -2v-1" />
    <path
      d="M20.385 6.585a2.1 2.1 0 0 0 -2.97 -2.97l-8.415 8.385v3h3l8.385 -8.415z"
    />
  </svg>
</a>
```

### Button Group (Recommended)

```html
<div class="btn-group-modern">
  <!-- Edit Button -->
  <a
    href="/edit/1"
    class="btn btn-sm btn-action btn-action-primary"
    title="Edit"
  >
    <svg><!-- Edit icon --></svg>
  </a>

  <!-- View Button -->
  <a
    href="/view/1"
    class="btn btn-sm btn-action btn-action-success"
    title="View"
  >
    <svg><!-- Eye icon --></svg>
  </a>

  <!-- Delete Button -->
  <a
    href="/delete/1"
    class="btn btn-sm btn-action btn-action-danger"
    title="Delete"
  >
    <svg><!-- Trash icon --></svg>
  </a>
</div>
```

## 🔧 Controller Example (DataTables)

```php
->addColumn('actions', function ($item) {
    $actions = [];
    $actions[] = '<div class="btn-group-modern" role="group">';

    // Edit button
    $editIcon = '<svg xmlns="http://www.w3.org/2000/svg" class="icon icon-sm" width="16" height="16" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M7 7h-1a2 2 0 0 0 -2 2v9a2 2 0 0 0 2 2h9a2 2 0 0 0 2 -2v-1" /><path d="M20.385 6.585a2.1 2.1 0 0 0 -2.97 -2.97l-8.415 8.385v3h3l8.385 -8.415z" /><path d="M16 5l3 3" /></svg>';
    $actions[] = link_to_route('items.edit', $editIcon, $item->id, [
        'class' => 'btn btn-sm btn-action btn-action-primary',
        'title' => 'Edit',
        'data-bs-toggle' => 'tooltip'
    ]);

    // View button
    $viewIcon = '<svg xmlns="http://www.w3.org/2000/svg" class="icon icon-sm" width="16" height="16" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M10 12a2 2 0 1 0 4 0a2 2 0 0 0 -4 0" /><path d="M21 12c-2.4 4 -5.4 6 -9 6c-3.6 0 -6.6 -2 -9 -6c2.4 -4 5.4 -6 9 -6c3.6 0 6.6 2 9 6" /></svg>';
    $actions[] = link_to_route('items.show', $viewIcon, $item->id, [
        'class' => 'btn btn-sm btn-action btn-action-success',
        'title' => 'View'
    ]);

    // Delete button
    $deleteIcon = '<svg xmlns="http://www.w3.org/2000/svg" class="icon icon-sm" width="16" height="16" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M4 7l16 0" /><path d="M10 11l0 6" /><path d="M14 11l0 6" /><path d="M5 7l1 12a2 2 0 0 0 2 2h8a2 2 0 0 0 2 -2l1 -12" /><path d="M9 7v-3a1 1 0 0 1 1 -1h4a1 1 0 0 1 1 1v3" /></svg>';
    $actions[] = '<a href="' . route('items.destroy', $item->id) . '" class="btn btn-sm btn-action btn-action-danger" title="Delete" onclick="return confirm(\'Are you sure?\')">' . $deleteIcon . '</a>';

    $actions[] = '</div>';
    return implode(' ', $actions);
})
->rawColumns(['actions'])
```

## 📐 Size Variants

### Default (32px)

```html
<a href="#" class="btn btn-sm btn-action btn-action-primary">
  <svg class="icon icon-sm" width="16" height="16">...</svg>
</a>
```

### Large (40px)

```html
<a href="#" class="btn btn-sm btn-action btn-action-lg btn-action-primary">
  <svg class="icon" width="20" height="20">...</svg>
</a>
```

### Small (28px)

```html
<a href="#" class="btn btn-sm btn-action btn-action-sm btn-action-primary">
  <svg class="icon" width="14" height="14">...</svg>
</a>
```

## 🎭 Icon Library (Tabler Icons)

Common SVG icons you can use:

### Edit Icon

```html
<svg
  xmlns="http://www.w3.org/2000/svg"
  class="icon icon-sm"
  width="16"
  height="16"
  viewBox="0 0 24 24"
  stroke-width="2"
  stroke="currentColor"
  fill="none"
  stroke-linecap="round"
  stroke-linejoin="round"
>
  <path stroke="none" d="M0 0h24v24H0z" fill="none" />
  <path d="M7 7h-1a2 2 0 0 0 -2 2v9a2 2 0 0 0 2 2h9a2 2 0 0 0 2 -2v-1" />
  <path
    d="M20.385 6.585a2.1 2.1 0 0 0 -2.97 -2.97l-8.415 8.385v3h3l8.385 -8.415z"
  />
  <path d="M16 5l3 3" />
</svg>
```

### View/Eye Icon

```html
<svg
  xmlns="http://www.w3.org/2000/svg"
  class="icon icon-sm"
  width="16"
  height="16"
  viewBox="0 0 24 24"
  stroke-width="2"
  stroke="currentColor"
  fill="none"
  stroke-linecap="round"
  stroke-linejoin="round"
>
  <path stroke="none" d="M0 0h24v24H0z" fill="none" />
  <path d="M10 12a2 2 0 1 0 4 0a2 2 0 0 0 -4 0" />
  <path
    d="M21 12c-2.4 4 -5.4 6 -9 6c-3.6 0 -6.6 -2 -9 -6c2.4 -4 5.4 -6 9 -6c3.6 0 6.6 2 9 6"
  />
</svg>
```

### Delete/Trash Icon

```html
<svg
  xmlns="http://www.w3.org/2000/svg"
  class="icon icon-sm"
  width="16"
  height="16"
  viewBox="0 0 24 24"
  stroke-width="2"
  stroke="currentColor"
  fill="none"
  stroke-linecap="round"
  stroke-linejoin="round"
>
  <path stroke="none" d="M0 0h24v24H0z" fill="none" />
  <path d="M4 7l16 0" />
  <path d="M10 11l0 6" />
  <path d="M14 11l0 6" />
  <path d="M5 7l1 12a2 2 0 0 0 2 2h8a2 2 0 0 0 2 -2l1 -12" />
  <path d="M9 7v-3a1 1 0 0 1 1 -1h4a1 1 0 0 1 1 1v3" />
</svg>
```

### Eye-Off (Unpublish) Icon

```html
<svg
  xmlns="http://www.w3.org/2000/svg"
  class="icon icon-sm"
  width="16"
  height="16"
  viewBox="0 0 24 24"
  stroke-width="2"
  stroke="currentColor"
  fill="none"
  stroke-linecap="round"
  stroke-linejoin="round"
>
  <path stroke="none" d="M0 0h24v24H0z" fill="none" />
  <path d="M3 3l18 18" />
  <path d="M10.584 10.587a2 2 0 0 0 2.828 2.83" />
  <path
    d="M9.363 5.365a9.466 9.466 0 0 1 2.637 -.365c4 0 7.333 2.333 10 7c-.778 1.361 -1.612 2.524 -2.503 3.488m-2.14 1.861c-1.631 1.1 -3.415 1.651 -5.357 1.651c-4 0 -7.333 -2.333 -10 -7c1.369 -2.395 2.913 -4.175 4.632 -5.341"
  />
</svg>
```

## ⚙️ Special States

### Disabled

```html
<a href="#" class="btn btn-sm btn-action btn-action-primary disabled">
  <svg>...</svg>
</a>
```

### Loading

```html
<button class="btn btn-sm btn-action btn-action-primary loading">
  <svg>...</svg>
</button>
```

## 🎨 Features

- ✨ Beautiful gradient backgrounds
- 🎭 Smooth hover animations (lift effect)
- 📱 Responsive (adjusts on mobile)
- 💡 Automatic tooltips on hover
- ⚡ Fast and lightweight
- 🎯 Consistent sizing and spacing

## 📚 Real Examples

Check these files for implementation examples:

- `app/Http/Controllers/BannersController.php`
- `resources/views/banners/index.blade.php`

## 🔗 More Icons

Visit [Tabler Icons](https://tabler.io/icons) to find more icons.

## 💡 Tips

1. Always use `btn-group-modern` wrapper for multiple buttons
2. Keep tooltip text concise
3. Use consistent icon sizes (16x16 for default buttons)
4. Don't forget `data-bs-toggle="tooltip"` for Bootstrap tooltips
5. Always set meaningful `title` attributes
