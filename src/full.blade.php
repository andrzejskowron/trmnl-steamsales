@props(['size' => 'full'])
<x-trmnl::view size="{{ $size }}">
  @php
    $items = $data['specials']['items'] ?? [];
    $totalItems = is_array($items) ? count($items) : 0;

    $selectedItems = [];
    if ($totalItems > 0) {
      usort($items, fn($a, $b) => ($b['discount_percent'] ?? 0) <=> ($a['discount_percent'] ?? 0));
      $selectedItems = array_slice($items, 0, min(3, $totalItems));
      while (count($selectedItems) < 3 && count($selectedItems) > 0) {
        $selectedItems[] = $selectedItems[0];
      }
    }

    function getDaysUntilExpiration($timestamp) {
      if (!$timestamp) return 'Unknown';
      $expirationDate = date('Y-m-d', $timestamp);
      $currentDate = date('Y-m-d');
      $diff = (strtotime($expirationDate) - strtotime($currentDate)) / (60 * 60 * 24);
      return max(0, ceil($diff));
    }

    function getControllerSupport($support) {
      if (!$support) return 'Unknown';
      switch (strtolower($support)) {
        case 'full':
          return 'Full Controller Support';
        case 'partial':
          return 'Partial Controller Support';
        default:
          return ucfirst($support) . ' Controller Support';
      }
    }
  @endphp

  @if(empty($selectedItems))
    <div>No data available to display.</div>
    @php return; @endphp
  @endif

  <div class="deals-container">
    <div class="deals-grid">
      @foreach($selectedItems as $item)
        <div class="deal-card bg--gray-7">
          <div class="deal-image-section">
            <img class="image-dither deal-image" src="{{ $item['small_capsule_image'] ?? '' }}" alt="Game cover">
            <div data-pixel-perfect="true" class="discount-badge">{{ $item['discount_percent'] ?? 0 }}% OFF</div>
          </div>

          <div class="deal-content">
            <h3 data-pixel-perfect="true" class="deal-game-title text--black">{{ $item['name'] ?? 'Unknown Game' }}</h3>

            <div class="price-section">
              <div data-pixel-perfect="true" class="current-price">
                {{ number_format(($item['final_price'] ?? 0) / 100, 2) }} {{ $item['currency'] ?? '$' }}
              </div>
              <div class="original-price">
                Was {{ number_format(($item['original_price'] ?? 0) / 100, 2) }} {{ $item['currency'] ?? '$' }}
              </div>
            </div>

            <div class="deal-info">
              <div class="info-item">
                <span class="info-label">Expires in:</span>
                <span data-pixel-perfect="true" class="info-value">{{ getDaysUntilExpiration($item['discount_expiration'] ?? null) }} days</span>
              </div>
              <div class="info-item">
                <span class="info-label">Controller:</span>
                <span class="info-value">{{ getControllerSupport($item['controller_support'] ?? null) }}</span>
              </div>
            </div>
          </div>
        </div>
      @endforeach
    </div>
  </div>

  <div class="title_bar">
    <img class="image-dither image-stroke image-stroke--small image" src="https://upload.wikimedia.org/wikipedia/commons/8/83/Steam_icon_logo.svg" alt="Steam Logo" />
    <span class="title">Steam Specials</span>
    <span class="instance">Daily Deals</span>
  </div>

  <style>
    .deals-container {
      width: 100%;
      max-width: 800px;
      margin: 0 auto;
      font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', system-ui, sans-serif;
      background: #fff;
    }

    .trmnl .title_bar {
      margin-top: 2px!important;
    }

    .deals-header {
      padding: 24px 0;
      text-align: center;
      border-bottom: 2px solid #000;
      margin-bottom: 24px;
    }

    .deals-title {
      font-size: 3rem;
      font-weight: 800;
      color: #000;
      margin: 0;
      letter-spacing: -0.025em;
      text-transform: uppercase;
      font-family: monospace;
    }

    .deals-grid {
      display: grid;
      grid-template-columns: repeat(3, 1fr);
      gap: 14px;
    }

    .deal-card {
      border: 3px solid #000;
      background: #fff;
      display: flex;
      flex-direction: column;
      overflow: hidden;
    }

    .deal-image-section {
      position: relative;
      height: 140px;
      background: #000;
      border-bottom: 2px solid #000;
    }

    .deal-image {
      width: 100%;
      height: 100%;
      object-fit: cover;
      filter: contrast(1.5) invert(1);
    }

    .discount-badge {
      position: absolute;
      top: 8px;
      right: 8px;
      background: #000;
      color: #fff;
      padding: 6px 12px;
      font-weight: 800;
      font-size: 1rem;
      font-family: monospace;
      border: none;
    }

    .deal-content {
      padding: 10px;
      color: #000;
      display: flex;
      flex-direction: column;
      gap: 16px;
    }

    .deal-game-title {
      font-size: 1.25rem;
      font-weight: 900;
      margin: 0;
      color: #000;
      line-height: 1.2;
      min-height: 1rem;
      display: -webkit-box;
      -webkit-line-clamp: 2;
      -webkit-box-orient: vertical;
      font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', system-ui, sans-serif;
    }

    .price-section {
      border: 2px solid #000;
      padding: 12px;
      background: #f8f8f8;
    }

    .current-price {
      font-size: 1.75rem;
      font-weight: 800;
      color: #000;
      margin: 0 0 4px 0;
      line-height: 1;
      font-family: monospace;
    }

    .original-price {
      font-size: 1rem;
      color: #666;
      text-decoration: line-through;
      font-weight: 600;
      font-family: monospace;
    }

    .deal-info {
      margin-top: auto;
      border-top: 2px solid #000;
      padding-top: 16px;
      display: flex;
      flex-direction: column;
      gap: 8px;
    }

    .info-item {
      display: flex;
      justify-content: space-between;
      align-items: center;
      font-size: 0.95rem;
      font-weight: 600;
      font-family: monospace;
    }

    .info-label {
      color: #666;
      font-weight: 700;
      text-transform: uppercase;
      font-size: 0.85rem;
      letter-spacing: 0.05em;
    }

    .info-value {
      color: #000;
      font-weight: 700;
      text-align: right;
      font-family: monospace;
    }

    .title_bar {
      display: flex;
      align-items: center;
      justify-content: center;
      gap: 10px;
      padding: 12px;
      border-top: 2px solid #000;
      border-bottom: 2px solid #000;
      margin-top: 20px;
      font-family: monospace;
    }

    .title_bar .title {
      font-weight: bold;
      font-size: 1.25rem;
    }

    .title_bar .instance {
      color: #666;
      font-size: 0.95rem;
    }

    .image-dither {
      image-rendering: pixelated;
    }

    .image-stroke--small {
      width: 32px;
      height: 32px;
    }

    .image {
      display: inline-block;
    }
  </style>
</x-trmnl::view>
