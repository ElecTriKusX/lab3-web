import '../scss/app.scss';
import * as bootstrap from 'bootstrap';
import '@fortawesome/fontawesome-free/css/all.min.css';

const products = [
  {
    id: 1,
    title: "Черника",
    category: "fruit",
    imagePath: 'images/cards/items/Blueberry/Blueberry_Stage_5.png',
    shortText: "Любимая многими ягода, о пользе которой написано немало статей. В ее кожуре много питательных веществ.",
    fullText: "Черника - это многолетний низкорослый кустарник высотой 15-30 см. Ягоды черники сине-чёрные, с сизым налётом, мякоть тёмно-красная, мягкая, с большим количеством семян. Созревает в июле-августе. Черника богата витаминами и антиоксидантами."
  },
  {
    id: 2,
    title: "Клубника",
    category: "fruit", 
    imagePath: 'images/cards/items/Strawberry/Strawberry_Stage_5.png',
    shortText: "Любимая многими ягода, о пользе которой написано немало статей. В ее кожуре много питательных веществ.",
    fullText: "Клубника - многолетнее травянистое растение. Ягоды ярко-красные, ароматные, сочные. Содержит большое количество витамина C, фолиевой кислоты и антиоксидантов."
  },
  {
    id: 3,
    title: "Карамбола",
    category: "fruit",
    imagePath: 'images/cards/items/Starfruit/Starfruit_Stage_5.png', 
    shortText: "Чрезвычайно сочный плод, растущий в жарком и влажном климате. Немного сладкий, с кислыми нотками.",
    fullText: "Карамбола - вечнозелёное дерево высотой до 5 м. Плоды жёлтые, с характерными рёбрами, в поперечном разрезе напоминают звезду. Вкус кисло-сладкий, освежающий."
  },
  {
    id: 4,
    title: "Древний плод",
    category: "fruit",
    imagePath: 'images/cards/items/Ancient_Fruit/Ancient_Fruit_Stage_5.png',
    shortText: "Он дремал целые эпохи.",
    fullText: "Загадочный древний плод, происхождение которого окутано тайной. Обладает уникальными свойствами и необычным вкусом, который невозможно сравнить ни с одним известным фруктом."
  },
  {
    id: 5, 
    title: "Тыква",
    category: "vegetable",
    imagePath: 'images/cards/items/Pumkin/Pumkin_Stage_5.png',
    shortText: "Выращивается осенью ради её хрустящих семян и деликатной мякоти. Кожуру можно превратить в праздничное украшение.",
    fullText: "Тыква - однолетнее травянистое растение. Плоды крупные, округлые, с твёрдой коркой и сочной мякотью оранжевого цвета. Богата каротином, витаминами и минералами."
  }
];

function getImageUrl(imagePath) {
  if (import.meta.env.DEV) {
    return `/${imagePath}`;
  }
  return `/build/assets/${imagePath}`;
}

document.addEventListener('DOMContentLoaded', function() {
  renderProducts();
  initEventListeners();
  initModalNavigation();
  initPopovers();
});

// Рендеринг продуктов
function renderProducts() {
  const container = document.getElementById('productsContainer');
  
  products.forEach(product => {
    const col = document.createElement('div');
    col.className = 'col-12 col-sm-6 col-lg-4 col-xl-3 col-xxxl mb-4';
    
    const categoryClass = `category-${product.category}`;
    const imageUrl = getImageUrl(product.imagePath);
    
    col.innerHTML = `
      <div class="card h-100">
        <img src="${imageUrl}" class="card-img-top img-fluid" alt="${product.title}" loading="lazy">
        <div class="card-body">
          <span class="badge ${categoryClass} position-absolute top-0 start-0 m-2">${getCategoryText(product.category)}</span>
          <h5 class="card-title">${product.title}</h5>
          <p class="card-text">${product.shortText}</p>
          <button class="btn btn-primary" onclick="openModal(${product.id})">Подробнее</button>
        </div>
      </div>
    `;
    
    container.appendChild(col);
  });
}

function getCategoryText(category) {
  const categories = {
    fruit: 'Плод/Ягода',
    vegetable: 'Овощ/Злак', 
    flower: 'Цветок'
  };
  return categories[category] || 'Категория';
}

let currentProductIndex = 0;

function openModal(productId) {
  const productIndex = products.findIndex(p => p.id === productId);
  if (productIndex !== -1) {
    currentProductIndex = productIndex;
    showProductModal(currentProductIndex);
    
    const modal = new bootstrap.Modal(document.getElementById('productModal'));
    modal.show();
  }
}

function showProductModal(index) {
  const product = products[index];
  const modal = document.getElementById('productModal');
  
  modal.querySelector('.modal-title').textContent = product.title;
  modal.querySelector('.modal-image').src = getImageUrl(product.imagePath);
  modal.querySelector('.modal-image').alt = product.title;
  modal.querySelector('.modal-text').textContent = product.fullText;
  
  const popoverBtn = modal.querySelector('[data-bs-toggle="popover"]');
  popoverBtn.setAttribute('data-bs-content', getPopoverContent(product.category));

  const popover = bootstrap.Popover.getInstance(popoverBtn);
  if (popover) {
    popover.dispose();
  }
  new bootstrap.Popover(popoverBtn);
}

function getPopoverContent(category) {
  const contents = {
    fruit: 'Плоды и ягоды богаты витаминами и антиоксидантами. Рекомендуется употреблять в свежем виде.',
    vegetable: 'Овощи содержат клетчатку и минералы. Полезны для пищеварения и общего здоровья.',
    flower: 'Цветы часто используются в декоративных целях и могут иметь лечебные свойства.'
  };
  return contents[category] || 'Интересная информация о растении.';
}

function initPopovers() {
  const popoverTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="popover"]'));
  popoverTriggerList.map(function (popoverTriggerEl) {
    return new bootstrap.Popover(popoverTriggerEl);
  });
}

function initModalNavigation() {
  document.addEventListener('keydown', function(e) {
    const modal = document.getElementById('productModal');
    if (modal.classList.contains('show')) {
      if (e.key === 'ArrowLeft') {
        navigateModal(-1);
      } else if (e.key === 'ArrowRight') {
        navigateModal(1);
      }
    }
  });
}

function navigateModal(direction) {
  currentProductIndex += direction;
  
  if (currentProductIndex < 0) {
    currentProductIndex = products.length - 1;
  } else if (currentProductIndex >= products.length) {
    currentProductIndex = 0;
  }
  
  showProductModal(currentProductIndex);
}

function initEventListeners() {
  const downloadBtn = document.getElementById('downloadBtn');
  const toastEl = document.getElementById('liveToast');
  
  if (downloadBtn && toastEl) {
    downloadBtn.addEventListener('click', function() {
      const toast = new bootstrap.Toast(toastEl);
      toast.show();
    });
  }
}

window.openModal = openModal;