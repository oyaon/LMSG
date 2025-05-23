<?php
/**
 * Reusable UI Components
 * 
 * This file contains functions for rendering reusable UI components
 */

/**
 * Render a book card
 * 
 * @param array $book Book data
 * @param bool $showActions Whether to show action buttons
 * @return string HTML for the book card
 */
function renderBookCard($book, $showActions = true) {
    // If cover_image is a full path, use as is; if just a filename, prepend images/
    $coverImage = (strcasecmp($book['name'], 'Whispers For Ruponty') === 0) ? 'images/Whispers.png' : (!empty($book['cover_image']) ? (strpos($book['cover_image'], '/') === false ? 'images/' . htmlspecialchars($book['cover_image']) : htmlspecialchars($book['cover_image'])) : 'images/books1.png');
    $bookId = htmlspecialchars($book['id']);
    $bookTitle = htmlspecialchars($book['name']);
    $bookAuthor = htmlspecialchars($book['author']);
    $bookCategory = htmlspecialchars($book['category']);
    $bookQuantity = isset($book['quantity']) ? (int)$book['quantity'] : 0;
    $bookPrice = isset($book['price']) ? number_format($book['price'], 2) : '';
    $bookDescription = isset($book['description']) ? htmlspecialchars($book['description']) : '';

    // Truncate description to 90 chars, add tooltip for full description
    $descShort = mb_strlen($bookDescription) > 90 ? mb_substr($bookDescription, 0, 90) . '...' : $bookDescription;
    $descTooltip = mb_strlen($bookDescription) > 90 ? ' title="' . $bookDescription . '"' : '';

    // Quantity indicator
    if ($bookQuantity > 5) {
        $quantityIcon = '<span class="text-success me-1" aria-label="In stock" title="In stock"><i class="fas fa-check-circle"></i></span>';
    } elseif ($bookQuantity > 0) {
        $quantityIcon = '<span class="text-warning me-1" aria-label="Limited stock" title="Limited stock"><i class="fas fa-exclamation-triangle"></i></span>';
    } else {
        $quantityIcon = '<span class="text-danger me-1" aria-label="Out of stock" title="Out of stock"><i class="fas fa-times-circle"></i></span>';
    }

    // Add 'unavailable' class if book is out of stock
    $cardClass = 'card book-card h-100 shadow-sm animate__animated animate__fadeInUp';
    if ($bookQuantity <= 0) {
        $cardClass .= ' unavailable';
    }
    $html = '<div class="' . $cardClass . '" tabindex="0" aria-label="Book: ' . $bookTitle . '">';
    $html .= '<img src="' . $coverImage . '" class="card-img-top book-cover lazyload" alt="' . $bookTitle . ' cover">';
    $html .= '<div class="card-body d-flex flex-column">';
    $html .= '<h5 class="card-title book-title">' . $bookTitle . '</h5>';
    $html .= '<p class="card-text book-author">by ' . $bookAuthor . '</p>';
    $html .= '<p class="card-text book-category text-secondary small mb-2"><a href="category.php?c=' . urlencode($bookCategory) . '">' . $bookCategory . '</a></p>';
    $html .= '<p class="card-text book-description"' . $descTooltip . '>' . $descShort . '</p>';
    $html .= '<div class="d-flex justify-content-between align-items-center mb-2">';
    $html .= $quantityIcon;
    $html .= '<span class="badge bg-primary">' . $bookPrice . ' TK</span>';
    $html .= '<span class="badge bg-secondary">' . $bookQuantity . ' available</span>';
    $html .= '</div>';

    if ($showActions) {
        $html .= '<div class="d-grid gap-2">';
        $html .= '<a href="book-details.php?t=' . $bookId . '" class="btn btn-primary" aria-label="View details for ' . $bookTitle . '" aria-haspopup="true">View Details</a>';
        if ($bookQuantity > 0) {
            $html .= '<a href="borrow.php?t=' . $bookId . '&q=' . $bookQuantity . '" class="btn btn-outline-success borrow-btn" aria-label="Borrow ' . $bookTitle . '"><i class="fas fa-book-reader me-1"></i>Borrow</a>';
        } else {
            $html .= '<button class="btn btn-outline-secondary" disabled title="This book is currently out of stock" aria-disabled="true">Out of Stock</button>';
        }
        $html .= '</div>';
    }

    $html .= '</div></div>';
    $html .= '</div>';
    return $html;
}

/**
 * Render a pagination component
 * 
 * @param int $currentPage Current page number
 * @param int $totalPages Total number of pages
 * @param string $baseUrl Base URL for pagination links
 * @return string HTML for the pagination component
 */
function renderPagination($currentPage, $totalPages, $baseUrl) {
    if ($totalPages <= 1) {
        return '';
    }
    
    $html = '<nav aria-label="Page navigation"><ul class="pagination justify-content-center">';
    
    // Previous button
    if ($currentPage > 1) {
        $prevPage = $currentPage - 1;
        $html .= "<li class='page-item'><a class='page-link' href='{$baseUrl}page={$prevPage}' aria-label='Previous'><span aria-hidden='true'>&laquo;</span></a></li>";
    } else {
        $html .= "<li class='page-item disabled'><a class='page-link' href='#' aria-label='Previous'><span aria-hidden='true'>&laquo;</span></a></li>";
    }
    
    // Page numbers
    $startPage = max(1, $currentPage - 2);
    $endPage = min($totalPages, $currentPage + 2);
    
    if ($startPage > 1) {
        $html .= "<li class='page-item'><a class='page-link' href='{$baseUrl}page=1'>1</a></li>";
        if ($startPage > 2) {
            $html .= "<li class='page-item disabled'><a class='page-link' href='#'>...</a></li>";
        }
    }
    
    for ($i = $startPage; $i <= $endPage; $i++) {
        if ($i == $currentPage) {
            $html .= "<li class='page-item active'><a class='page-link' href='#'>{$i}</a></li>";
        } else {
            $html .= "<li class='page-item'><a class='page-link' href='{$baseUrl}page={$i}'>{$i}</a></li>";
        }
    }
    
    if ($endPage < $totalPages) {
        if ($endPage < $totalPages - 1) {
            $html .= "<li class='page-item disabled'><a class='page-link' href='#'>...</a></li>";
        }
        $html .= "<li class='page-item'><a class='page-link' href='{$baseUrl}page={$totalPages}'>{$totalPages}</a></li>";
    }
    
    // Next button
    if ($currentPage < $totalPages) {
        $nextPage = $currentPage + 1;
        $html .= "<li class='page-item'><a class='page-link' href='{$baseUrl}page={$nextPage}' aria-label='Next'><span aria-hidden='true'>&raquo;</span></a></li>";
    } else {
        $html .= "<li class='page-item disabled'><a class='page-link' href='#' aria-label='Next'><span aria-hidden='true'>&raquo;</span></a></li>";
    }
    
    $html .= '</ul></nav>';
    
    return $html;
}

/**
 * Render an alert message
 * 
 * @param string $message Alert message
 * @param string $type Alert type (success, danger, warning, info)
 * @param bool $dismissible Whether the alert can be dismissed
 * @return string HTML for the alert
 */
function renderAlert($message, $type = 'info', $dismissible = true) {
    $dismissibleClass = $dismissible ? 'alert-dismissible fade show' : '';
    $dismissButton = $dismissible ? '<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>' : '';
    
    return <<<HTML
    <div class="alert alert-{$type} {$dismissibleClass}" role="alert">
        {$message}
        {$dismissButton}
    </div>
    HTML;
}

/**
 * Render a form input field
 * 
 * @param string $type Input type (text, email, password, etc.)
 * @param string $name Input name
 * @param string $label Input label
 * @param string $value Input value
 * @param bool $required Whether the input is required
 * @param string $placeholder Input placeholder
 * @param string $helpText Help text to display below the input
 * @param array $attributes Additional input attributes
 * @return string HTML for the form input
 */
function renderFormInput($type, $name, $label, $value = '', $required = false, $placeholder = '', $helpText = '', $attributes = []) {
    $id = isset($attributes['id']) ? $attributes['id'] : $name;
    $requiredAttr = $required ? 'required' : '';
    $requiredStar = $required ? '<span class="text-danger">*</span>' : '';
    $placeholderAttr = $placeholder ? "placeholder=\"{$placeholder}\"" : '';
    $valueAttr = ($type !== 'password' && $value !== '') ? "value=\"{$value}\"" : '';
    
    // Build additional attributes
    $additionalAttrs = '';
    foreach ($attributes as $attr => $attrValue) {
        if ($attr !== 'id') {
            $additionalAttrs .= " {$attr}=\"{$attrValue}\"";
        }
    }
    
    $html = <<<HTML
    <div class="mb-3">
        <label for="{$id}" class="form-label">{$label} {$requiredStar}</label>
        <input type="{$type}" class="form-control" id="{$id}" name="{$name}" {$valueAttr} {$placeholderAttr} {$requiredAttr} {$additionalAttrs}>
    HTML;
    
    if ($helpText) {
        $html .= "<div class=\"form-text\">{$helpText}</div>";
    }
    
    $html .= '<div class="invalid-feedback">Please provide a valid ' . strtolower($label) . '.</div>';
    $html .= '</div>';
    
    return $html;
}

/**
 * Render a form select field
 * 
 * @param string $name Select name
 * @param string $label Select label
 * @param array $options Select options (key => value pairs)
 * @param string $selected Selected option value
 * @param bool $required Whether the select is required
 * @param string $helpText Help text to display below the select
 * @param array $attributes Additional select attributes
 * @return string HTML for the form select
 */
function renderFormSelect($name, $label, $options, $selected = '', $required = false, $helpText = '', $attributes = []) {
    $id = isset($attributes['id']) ? $attributes['id'] : $name;
    $requiredAttr = $required ? 'required' : '';
    $requiredStar = $required ? '<span class="text-danger">*</span>' : '';
    
    // Build additional attributes
    $additionalAttrs = '';
    foreach ($attributes as $attr => $attrValue) {
        if ($attr !== 'id') {
            $additionalAttrs .= " {$attr}=\"{$attrValue}\"";
        }
    }
    
    $html = <<<HTML
    <div class="mb-3">
        <label for="{$id}" class="form-label">{$label} {$requiredStar}</label>
        <select class="form-select" id="{$id}" name="{$name}" {$requiredAttr} {$additionalAttrs}>
    HTML;
    
    foreach ($options as $value => $text) {
        $selectedAttr = ($value == $selected) ? 'selected' : '';
        $html .= "<option value=\"{$value}\" {$selectedAttr}>{$text}</option>";
    }
    
    $html .= '</select>';
    
    if ($helpText) {
        $html .= "<div class=\"form-text\">{$helpText}</div>";
    }
    
    $html .= '<div class="invalid-feedback">Please select a ' . strtolower($label) . '.</div>';
    $html .= '</div>';
    
    return $html;
}

/**
 * Render a form textarea
 * 
 * @param string $name Textarea name
 * @param string $label Textarea label
 * @param string $value Textarea value
 * @param bool $required Whether the textarea is required
 * @param string $placeholder Textarea placeholder
 * @param string $helpText Help text to display below the textarea
 * @param array $attributes Additional textarea attributes
 * @return string HTML for the form textarea
 */
function renderFormTextarea($name, $label, $value = '', $required = false, $placeholder = '', $helpText = '', $attributes = []) {
    $id = isset($attributes['id']) ? $attributes['id'] : $name;
    $requiredAttr = $required ? 'required' : '';
    $requiredStar = $required ? '<span class="text-danger">*</span>' : '';
    $placeholderAttr = $placeholder ? "placeholder=\"{$placeholder}\"" : '';
    $rows = isset($attributes['rows']) ? $attributes['rows'] : '3';
    
    // Build additional attributes
    $additionalAttrs = '';
    foreach ($attributes as $attr => $attrValue) {
        if ($attr !== 'id' && $attr !== 'rows') {
            $additionalAttrs .= " {$attr}=\"{$attrValue}\"";
        }
    }
    
    $html = <<<HTML
    <div class="mb-3">
        <label for="{$id}" class="form-label">{$label} {$requiredStar}</label>
        <textarea class="form-control" id="{$id}" name="{$name}" rows="{$rows}" {$placeholderAttr} {$requiredAttr} {$additionalAttrs}>{$value}</textarea>
    HTML;
    
    if ($helpText) {
        $html .= "<div class=\"form-text\">{$helpText}</div>";
    }
    
    $html .= '<div class="invalid-feedback">Please provide a valid ' . strtolower($label) . '.</div>';
    $html .= '</div>';
    
    return $html;
}

/**
 * Render a form checkbox
 * 
 * @param string $name Checkbox name
 * @param string $label Checkbox label
 * @param bool $checked Whether the checkbox is checked
 * @param bool $required Whether the checkbox is required
 * @param string $helpText Help text to display below the checkbox
 * @param array $attributes Additional checkbox attributes
 * @return string HTML for the form checkbox
 */
function renderFormCheckbox($name, $label, $checked = false, $required = false, $helpText = '', $attributes = []) {
    $id = isset($attributes['id']) ? $attributes['id'] : $name;
    $requiredAttr = $required ? 'required' : '';
    $checkedAttr = $checked ? 'checked' : '';
    
    // Build additional attributes
    $additionalAttrs = '';
    foreach ($attributes as $attr => $attrValue) {
        if ($attr !== 'id') {
            $additionalAttrs .= " {$attr}=\"{$attrValue}\"";
        }
    }
    
    $html = <<<HTML
    <div class="mb-3 form-check">
        <input type="checkbox" class="form-check-input" id="{$id}" name="{$name}" {$checkedAttr} {$requiredAttr} {$additionalAttrs}>
        <label class="form-check-label" for="{$id}">{$label}</label>
    HTML;
    
    if ($helpText) {
        $html .= "<div class=\"form-text\">{$helpText}</div>";
    }
    
    $html .= '<div class="invalid-feedback">You must agree before submitting.</div>';
    $html .= '</div>';
    
    return $html;
}

/**
 * Render a star rating
 * 
 * @param float $rating Rating value (0-5)
 * @param bool $showValue Whether to show the numeric value
 * @return string HTML for the star rating
 */
function renderStarRating($rating, $showValue = true) {
    $rating = min(5, max(0, $rating));
    $fullStars = floor($rating);
    $halfStar = ($rating - $fullStars) >= 0.5;
    $emptyStars = 5 - $fullStars - ($halfStar ? 1 : 0);
    
    $html = '<div class="star-rating">';
    
    // Full stars
    for ($i = 0; $i < $fullStars; $i++) {
        $html .= '<i class="fas fa-star text-warning"></i>';
    }
    
    // Half star
    if ($halfStar) {
        $html .= '<i class="fas fa-star-half-alt text-warning"></i>';
    }
    
    // Empty stars
    for ($i = 0; $i < $emptyStars; $i++) {
        $html .= '<i class="far fa-star text-warning"></i>';
    }
    
    if ($showValue) {
        $html .= "<span class=\"ms-2 text-muted\">(" . number_format($rating, 1) . ")</span>";
    }
    
    $html .= '</div>';
    
    return $html;
}

/**
 * Render a search form
 * 
 * @param string $action Form action URL
 * @param string $placeholder Search input placeholder
 * @param string $buttonText Search button text
 * @param string $query Current search query
 * @param array $filters Additional search filters
 * @return string HTML for the search form
 */
function renderSearchForm($action, $placeholder = 'Search...', $buttonText = 'Search', $query = '', $filters = []) {
    $html = <<<HTML
    <form action="{$action}" method="GET" class="search-form">
        <div class="input-group mb-3">
            <input type="text" class="form-control" placeholder="{$placeholder}" name="q" value="{$query}">
            <button class="btn btn-primary" type="submit">{$buttonText}</button>
        </div>
    HTML;
    
    if (!empty($filters)) {
        $html .= '<div class="search-filters row">';
        foreach ($filters as $filter) {
            $col = isset($filter['col']) ? $filter['col'] : '3';
            $html .= "<div class=\"col-md-{$col}\">";
            
            if (isset($filter['type']) && $filter['type'] === 'select') {
                $value = isset($filter['value']) ? $filter['value'] : '';
                $html .= renderFormSelect(
                    $filter['name'],
                    $filter['label'],
                    $filter['options'],
                    $value,
                    false
                );
            } else if (isset($filter['type']) && $filter['type'] === 'checkbox') {
                $checked = isset($filter['checked']) ? $filter['checked'] : false;
                $html .= renderFormCheckbox(
                    $filter['name'],
                    $filter['label'],
                    $checked,
                    false
                );
            } else {
                $type = isset($filter['type']) ? $filter['type'] : 'text';
                $value = isset($filter['value']) ? $filter['value'] : '';
                $placeholderVal = isset($filter['placeholder']) ? $filter['placeholder'] : '';
                $html .= renderFormInput(
                    $type,
                    $filter['name'],
                    $filter['label'],
                    $value,
                    false,
                    $placeholderVal
                );
            }
            
            $html .= '</div>';
        }
        $html .= '</div>';
    }
    
    $html .= '</form>';
    
    return $html;
}

/**
 * Render a breadcrumb navigation
 * 
 * @param array $items Breadcrumb items (array of ['text' => string, 'url' => string] pairs)
 * @return string HTML for the breadcrumb
 */
function renderBreadcrumb($items) {
    if (empty($items)) {
        return '';
    }
    
    $html = '<nav aria-label="breadcrumb"><ol class="breadcrumb">';
    
    $lastIndex = count($items) - 1;
    foreach ($items as $index => $item) {
        if ($index === $lastIndex) {
            $html .= "<li class=\"breadcrumb-item active\" aria-current=\"page\">{$item['text']}</li>";
        } else {
            $html .= "<li class=\"breadcrumb-item\"><a href=\"{$item['url']}\">{$item['text']}</a></li>";
        }
    }
    
    $html .= '</ol></nav>';
    
    return $html;
}

/**
 * Render a tab navigation
 * 
 * @param string $id Tab container ID
 * @param array $tabs Tab items (array of ['id' => string, 'title' => string, 'content' => string] objects)
 * @param string $activeTab ID of the active tab
 * @return string HTML for the tab navigation
 */
function renderTabs($id, $tabs, $activeTab = '') {
    if (empty($tabs)) {
        return '';
    }
    
    if (empty($activeTab)) {
        $activeTab = $tabs[0]['id'];
    }
    
    $html = "<div class=\"tab-container\" id=\"{$id}\">";
    
    // Tab navigation
    $html .= '<ul class="nav nav-tabs" role="tablist">';
    foreach ($tabs as $tab) {
        $tabId = $tab['id'];
        $isActive = ($tabId === $activeTab) ? 'active' : '';
        $ariaSelected = ($tabId === $activeTab) ? 'true' : 'false';
        $html .= <<<HTML
        <li class="nav-item" role="presentation">
            <button class="nav-link {$isActive}" id="{$tabId}-tab" data-bs-toggle="tab" data-bs-target="#{$tabId}" type="button" role="tab" aria-controls="{$tabId}" aria-selected="{$ariaSelected}">{$tab['title']}</button>
        </li>
        HTML;
    }
    $html .= '</ul>';
    
 // Tab content
    $html .= '<div class="tab-content mt-3">';
    foreach ($tabs as $tab) {
        $tabId = $tab['id'];
        $isActive = ($tabId === $activeTab) ? 'show active' : '';
        $html .= <<<HTML
        <div class="tab-pane fade {$isActive}" id="{$tabId}" role="tabpanel" aria-labelledby="{$tabId}-tab">
            {$tab['content']}
        </div>
        HTML;
    }
    $html .= '</div>';
    
    $html .= '</div>';
    
    return $html;
}

/**
 * Get the book cover image
 * 
 * @param string $coverImage Cover image filename
 * @return string Full path to the cover image
 */
function getBookCoverImage($coverImage) {
    $defaultImage = "images/books1.png";
    $coverImagePath = "images/covers/" . $coverImage;

    if (!empty($coverImage) && file_exists($coverImagePath)) {
        return $coverImagePath;
    }

    return $defaultImage;
}