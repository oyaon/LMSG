        $tabId = $tab['id'];
        $isActive = ($tabId === $activeTab) ? 'active' : '';
        $ariaSelected = ($tabId === $activeTab) ? 'true' : 'false';
        $html .= <<<HTML
        <li class="nav-item" role="presentation">
            <button class="nav-link {$isActive}" id="{$tabId}-tab" data-bs-toggle="tab" data-bs-target="#{$tabId}" type="button" role="tab" aria-controls="{$tabId}" aria-selected="{$ariaSelected}">{$tab['title']}</button>
        </li>
        HTML;        $tabId = $tab['id'];
        $isActive = ($tabId === $activeTab) ? 'active' : '';
        $ariaSelected = ($tabId === $activeTab) ? 'true' : 'false';
        $html .= <<<HTML
        <li class="nav-item" role="presentation">
            <button class="nav-link {$isActive}" id="{$tabId}-tab" data-bs-toggle="tab" data-bs-target="#{$tabId}" type="button" role="tab" aria-controls="{$tabId}" aria-selected="{$ariaSelected}">{$tab['title']}</button>
        </li>
        HTML;