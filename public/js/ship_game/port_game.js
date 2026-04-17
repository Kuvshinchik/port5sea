// ═══════════════════════════════════════════════════════════════════════════
// СОСТОЯНИЕ ИГРЫ
// ═══════════════════════════════════════════════════════════════════════════

let gameState = {
    money: INITIAL_GAME_STATE.progress.money,
    crew: INITIAL_GAME_STATE.crew.map(c => c.id),
    equipment: INITIAL_GAME_STATE.equipment.filter(e => e.category === 'equipment').map(e => e.id),
    food: INITIAL_GAME_STATE.equipment.filter(e => e.category === 'food').map(e => e.id),
    medicine: INITIAL_GAME_STATE.equipment.filter(e => e.category === 'medicine').map(e => e.id),
    jobs: INITIAL_GAME_STATE.jobs.map(j => j.id),
    foodDays: INITIAL_GAME_STATE.progress.food_days,
    cargoUsed: INITIAL_GAME_STATE.progress.cargo_used,
    morale: INITIAL_GAME_STATE.progress.morale,
    fuelPercent: INITIAL_GAME_STATE.progress.fuel_percent,
    hasInfiniteFuel: INITIAL_GAME_STATE.has_infinite_fuel,
    hasInfiniteFood: INITIAL_GAME_STATE.has_infinite_food,
};

// ═══════════════════════════════════════════════════════════════════════════
// УТИЛИТЫ
// ═══════════════════════════════════════════════════════════════════════════

function formatMoney(amount) {
    return amount.toLocaleString('ru-RU') + ' ₽';
}

function getMoraleEmoji() {
    if (gameState.morale >= 80) return '😊 Отлично';
    if (gameState.morale >= 60) return '🙂 Хорошо';
    if (gameState.morale >= 40) return '😐 Нормально';
    if (gameState.morale >= 20) return '😟 Плохо';
    return '😢 Ужасно';
}

function showLoading() { document.getElementById('loading').style.display = 'flex'; }
function hideLoading() { document.getElementById('loading').style.display = 'none'; }

function showNotification(message, type = 'success') {
    const existing = document.querySelector('.game-notification');
    if (existing) existing.remove();
    
    const notif = document.createElement('div');
    notif.className = `game-notification ${type}`;
    notif.textContent = message;
    document.body.appendChild(notif);
    
    setTimeout(() => notif.remove(), 3000);
}

async function apiRequest(url, method = 'GET', data = null) {
    showLoading();
    
    try {
        const options = {
            method,
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': CSRF_TOKEN,
                'Accept': 'application/json',
            },
        };
        
        if (data && method !== 'GET') {
            options.body = JSON.stringify(data);
        }
        
        const response = await fetch(url, options);
        const result = await response.json();
        
        if (!response.ok) {
            throw new Error(result.message || 'Ошибка сервера');
        }
        
        if (result.data) updateLocalState(result.data);
        
        return result;
        
    } catch (error) {
        showNotification(error.message, 'error');
        throw error;
    } finally {
        hideLoading();
    }
}

function updateLocalState(serverState) {
    gameState.money = serverState.progress.money;
    gameState.foodDays = serverState.progress.food_days;
    gameState.cargoUsed = serverState.progress.cargo_used;
    gameState.morale = serverState.progress.morale;
    gameState.fuelPercent = serverState.progress.fuel_percent;
    gameState.crew = serverState.crew.map(c => c.id);
    gameState.equipment = serverState.equipment.filter(e => e.category === 'equipment').map(e => e.id);
    gameState.food = serverState.equipment.filter(e => e.category === 'food').map(e => e.id);
    gameState.medicine = serverState.equipment.filter(e => e.category === 'medicine').map(e => e.id);
    gameState.jobs = serverState.jobs.map(j => j.id);
    
    updateStatusPanel();
}

// ═══════════════════════════════════════════════════════════════════════════
// PHASER
// ═══════════════════════════════════════════════════════════════════════════

const config = {
    type: Phaser.AUTO,
    width: 1200,
    height: 800,
    parent: 'game-container',
    backgroundColor: '#1a3a4a',
    scene: { preload, create, update }
};

const game = new Phaser.Game(config);

let currentModal = null;
let statusPanel = null;
let gameScene = null;

function preload() {
    this.load.image('port_bg', '/assets/game/murmansk/port/port.png');
    this.load.image('locker1', '/assets/game/murmansk/port/locker1.png');
    this.load.image('locker2', '/assets/game/murmansk/port/locker2.png');
    this.load.image('locker3', '/assets/game/murmansk/port/locker3.png');
    this.load.image('locker4', '/assets/game/murmansk/port/locker4.png');
}

function create() {
    gameScene = this;
    
    const portBg = this.add.image(600, 400, 'port_bg');
    portBg.setDisplaySize(1200, 800);
    
    createBuildings(this);
    createStatusPanel(this);
    createSnowEffect(this);
    
    document.getElementById('btn-depart').addEventListener('click', () => checkReadyToDepart(this));
}

function update() {}

// ═══════════════════════════════════════════════════════════════════════════
// ЗДАНИЯ
// ═══════════════════════════════════════════════════════════════════════════

function createBuildings(scene) {
    const buildings = [
        { x: 200, y: 620, imageKey: 'locker1', action: () => openCrewModal(scene) },
        { x: 450, y: 620, imageKey: 'locker2', action: () => openEquipmentModal(scene) },
        { x: 700, y: 620, imageKey: 'locker3', action: () => openFoodModal(scene) },
        { x: 950, y: 620, imageKey: 'locker4', action: () => openJobsModal(scene) }
    ];
    
    buildings.forEach(b => createBuildingFromImage(scene, b));
}

function createBuildingFromImage(scene, config) {
    const container = scene.add.container(config.x, config.y);
    
    const buildingImage = scene.add.image(0, 0, config.imageKey);
    buildingImage.setOrigin(0.5, 1);
    container.add(buildingImage);
    
    const width = buildingImage.width;
    const height = buildingImage.height;
    
    const hitArea = scene.add.rectangle(0, -height / 2, width, height, 0xffffff, 0);
    hitArea.setInteractive({ useHandCursor: true });
    container.add(hitArea);
    
    hitArea.on('pointerover', () => {
        scene.tweens.add({ targets: container, scaleX: 1.05, scaleY: 1.05, y: config.y - 10, duration: 150, ease: 'Back.easeOut' });
    });
    
    hitArea.on('pointerout', () => {
        scene.tweens.add({ targets: container, scaleX: 1, scaleY: 1, y: config.y, duration: 150, ease: 'Back.easeIn' });
    });
    
    hitArea.on('pointerdown', () => {
        scene.tweens.add({ targets: container, scaleX: 0.95, scaleY: 0.95, duration: 50, yoyo: true, onComplete: config.action });
    });
    
    return container;
}

function createSnowEffect(scene) {
    const snowGraphics = scene.make.graphics({ x: 0, y: 0, add: false });
    snowGraphics.fillStyle(0xffffff, 1);
    snowGraphics.fillCircle(4, 4, 4);
    snowGraphics.generateTexture('snowflake', 8, 8);
    snowGraphics.destroy();
    
    const snowEmitter = scene.add.particles(0, -10, 'snowflake', {
        x: { min: 0, max: 1200 }, y: -10, lifespan: 8000, speedY: { min: 30, max: 80 },
        speedX: { min: -20, max: 20 }, scale: { min: 0.2, max: 0.8 }, alpha: { start: 0.8, end: 0.3 },
        rotate: { min: 0, max: 360 }, frequency: 50, quantity: 2, blendMode: 'ADD'
    });
    snowEmitter.setDepth(100);
}

// ═══════════════════════════════════════════════════════════════════════════
// ПАНЕЛЬ СТАТУСА
// ═══════════════════════════════════════════════════════════════════════════

function createStatusPanel(scene) {
    statusPanel = scene.add.container(600, 760);
    
    const panelBg = scene.add.graphics();
    panelBg.fillStyle(0x1a1a2e, 0.95);
    panelBg.fillRoundedRect(-580, -35, 1160, 70, 15);
    panelBg.lineStyle(2, 0x4fc3f7, 0.5);
    panelBg.strokeRoundedRect(-580, -35, 1160, 70, 15);
    statusPanel.add(panelBg);
    
    const items = [
        { key: 'money', icon: '💰', label: 'Деньги', getValue: () => formatMoney(gameState.money) },
        { key: 'crew', icon: '🧑‍✈️', label: 'Экипаж', getValue: () => `${gameState.crew.length} / ${MAX_CREW}` },
        { key: 'food', icon: '🍞', label: 'Еда', getValue: () => gameState.hasInfiniteFood ? '∞' : `${gameState.foodDays} дней` },
        { key: 'fuel', icon: '⛽', label: 'Топливо', getValue: () => gameState.hasInfiniteFuel ? '∞' : `${gameState.fuelPercent}%` },
        { key: 'cargo', icon: '⚖️', label: 'Груз', getValue: () => `${gameState.cargoUsed} / ${MAX_CARGO_CAPACITY}` },
        { key: 'morale', icon: '😊', label: 'Мораль', getValue: () => getMoraleEmoji() }
    ];
    
    const startX = -500;
    const spacing = 190;
    
    items.forEach((item, index) => {
        const x = startX + index * spacing;
        
        const icon = scene.add.text(x, -5, item.icon, { fontSize: '24px' });
        icon.setOrigin(0.5);
        statusPanel.add(icon);
        
        const label = scene.add.text(x + 25, -15, item.label, { fontSize: '12px', fontFamily: 'Nunito, Arial', color: '#aaaaaa' });
        statusPanel.add(label);
        
        const value = scene.add.text(x + 25, 5, item.getValue(), { fontSize: '14px', fontFamily: 'Nunito, Arial', color: '#ffffff', fontStyle: 'bold' });
        value.setData('updateFunc', item.getValue);
        statusPanel.add(value);
    });
}

function updateStatusPanel() {
    if (!statusPanel) return;
    statusPanel.list.forEach(child => {
        if (child.getData && child.getData('updateFunc')) {
            child.setText(child.getData('updateFunc')());
        }
    });
}

// ═══════════════════════════════════════════════════════════════════════════
// МОДАЛЬНЫЕ ОКНА
// ═══════════════════════════════════════════════════════════════════════════

function createModal(scene, title, width, height) {
    if (currentModal) closeModal(scene, currentModal);
    
    const modal = scene.add.container(600, 400);
    modal.setDepth(1000);
    
    const overlay = scene.add.rectangle(0, 0, 1200, 800, 0x000000, 0.7);
    overlay.setInteractive();
    modal.add(overlay);
    
    const modalBg = scene.add.graphics();
    modalBg.fillStyle(0x1a1a2e, 0.98);
    modalBg.fillRoundedRect(-width/2, -height/2, width, height, 20);
    modalBg.lineStyle(3, 0x4fc3f7, 0.8);
    modalBg.strokeRoundedRect(-width/2, -height/2, width, height, 20);
    modal.add(modalBg);
    
    const titleText = scene.add.text(0, -height/2 + 30, title, { fontSize: '24px', fontFamily: 'Russo One, Arial', color: '#4fc3f7' });
    titleText.setOrigin(0.5);
    modal.add(titleText);
    
    const closeBtn = scene.add.text(width/2 - 30, -height/2 + 15, '✕', { fontSize: '28px', color: '#e74c3c' });
    closeBtn.setOrigin(0.5);
    closeBtn.setInteractive({ useHandCursor: true });
    closeBtn.on('pointerdown', () => closeModal(scene, modal));
    closeBtn.on('pointerover', () => closeBtn.setColor('#ff6b6b'));
    closeBtn.on('pointerout', () => closeBtn.setColor('#e74c3c'));
    modal.add(closeBtn);
    
    modal.setScale(0.8);
    modal.setAlpha(0);
    scene.tweens.add({ targets: modal, scaleX: 1, scaleY: 1, alpha: 1, duration: 200, ease: 'Back.easeOut' });
    
    currentModal = modal;
    return modal;
}

function closeModal(scene, modal) {
    if (!modal) return;
    scene.tweens.add({
        targets: modal, scaleX: 0.8, scaleY: 0.8, alpha: 0, duration: 150, ease: 'Back.easeIn',
        onComplete: () => { modal.destroy(); if (currentModal === modal) currentModal = null; }
    });
}

// ═══════════════════════════════════════════════════════════════════════════
// ОКНО ЭКИПАЖА
// ═══════════════════════════════════════════════════════════════════════════

function openCrewModal(scene) {
    const modal = createModal(scene, '👨‍✈️ КАДРОВОЕ АГЕНТСТВО', 950, 650);
    
    const cols = 2, cardWidth = 400, cardHeight = 70, gapX = 20, gapY = 10;
    const totalWidth = cols * cardWidth + (cols - 1) * gapX;
    const startX = -totalWidth / 2, startY = -220;
    
    CREW_MEMBERS.forEach((member, index) => {
        const col = index % cols, row = Math.floor(index / cols);
        const x = startX + col * (cardWidth + gapX);
        const y = startY + row * (cardHeight + gapY);
        const card = createCrewCard(scene, member, x, y, cardWidth, cardHeight);
        modal.add(card);
    });
}

function createCrewCard(scene, member, x, y, width, height) {
    const card = scene.add.container(x, y);
    const isHired = gameState.crew.includes(member.id);
    
    const bg = scene.add.graphics();
    bg.fillStyle(isHired ? 0x27ae60 : (member.isFromToy ? 0x2a4a6a : 0x2a2a4a), 1);
    bg.fillRoundedRect(0, 0, width, height, 8);
    if (member.required && !isHired) { bg.lineStyle(2, 0xf39c12, 1); bg.strokeRoundedRect(0, 0, width, height, 8); }
    if (member.isFromToy) { bg.lineStyle(2, 0x4fc3f7, 0.5); bg.strokeRoundedRect(0, 0, width, height, 8); }
    card.add(bg);
    
    const roleIcon = scene.add.text(15, height/2, member.roleIcon, { fontSize: '24px' });
    roleIcon.setOrigin(0, 0.5);
    card.add(roleIcon);
    
    const nameText = scene.add.text(50, 12, member.name + (member.isFromToy ? ' 🎁' : ''), { fontSize: '13px', fontFamily: 'Nunito, Arial', color: '#ffffff', fontStyle: 'bold' });
    card.add(nameText);
    
    const roleText = scene.add.text(50, 30, `${member.role} ${'⭐'.repeat(member.skill)}`, { fontSize: '11px', fontFamily: 'Nunito, Arial', color: '#aaaaaa' });
    card.add(roleText);
    
    const salaryText = scene.add.text(50, 48, `💰 ${formatMoney(member.salary)}/день`, { fontSize: '10px', fontFamily: 'Nunito, Arial', color: '#f1c40f' });
    card.add(salaryText);
    
    const btnX = width - 45, btnY = 15, btnW = 35, btnH = 40;
    const btnColor = isHired ? 0xe74c3c : 0x3498db;
    const btnText = isHired ? '✕' : '+';
    
    const btn = scene.add.graphics();
    btn.fillStyle(btnColor, 1);
    btn.fillRoundedRect(btnX, btnY, btnW, btnH, 6);
    card.add(btn);
    
    const btnLabel = scene.add.text(btnX + btnW/2, btnY + btnH/2, btnText, { fontSize: '20px', fontFamily: 'Nunito, Arial', color: '#ffffff', fontStyle: 'bold' });
    btnLabel.setOrigin(0.5);
    card.add(btnLabel);
    
    const btnHit = scene.add.zone(btnX + btnW/2, btnY + btnH/2, btnW, btnH);
    btnHit.setInteractive({ useHandCursor: true });
    btnHit.on('pointerdown', () => toggleCrewMember(scene, member));
    card.add(btnHit);
    
    if (member.required && !isHired) {
        const reqLabel = scene.add.text(btnX, 3, '⚠️', { fontSize: '12px' });
        card.add(reqLabel);
    }
    
    return card;
}

async function toggleCrewMember(scene, member) {
    const isHired = gameState.crew.includes(member.id);
    
    try {
        if (isHired) {
            const result = await apiRequest(API_ROUTES.fireCrew, 'POST', { member_id: member.id });
            showNotification(result.message, 'success');
        } else {
            if (gameState.crew.length >= MAX_CREW) { showNotification('Экипаж укомплектован!', 'error'); return; }
            const result = await apiRequest(API_ROUTES.hireCrew, 'POST', { member_id: member.id });
            showNotification(result.message, 'success');
        }
        closeModal(scene, currentModal);
        setTimeout(() => openCrewModal(scene), 200);
    } catch (error) {}
}

// ═══════════════════════════════════════════════════════════════════════════
// ОКНО СНАРЯЖЕНИЯ
// ═══════════════════════════════════════════════════════════════════════════

function openEquipmentModal(scene) {
    const modal = createModal(scene, '🧰 СКЛАД СНАРЯЖЕНИЯ', 850, 500);
    
    const cols = 2, cardWidth = 380, cardHeight = 50, gapX = 20, gapY = 8;
    const totalWidth = cols * cardWidth + (cols - 1) * gapX;
    const startX = -totalWidth / 2, startY = -160;
    
    EQUIPMENT_ITEMS.forEach((item, index) => {
        const col = index % cols, row = Math.floor(index / cols);
        const x = startX + col * (cardWidth + gapX);
        const y = startY + row * (cardHeight + gapY);
        const card = createItemCard(scene, item, x, y, cardWidth, cardHeight, 'equipment');
        modal.add(card);
    });
}

function openFoodModal(scene) {
    const modal = createModal(scene, '🍞 ПРОДОВОЛЬСТВЕННЫЙ СКЛАД', 850, 600);
    
    const cols = 2, cardWidth = 380, cardHeight = 50, gapX = 20, gapY = 8;
    const totalWidth = cols * cardWidth + (cols - 1) * gapX;
    const startX = -totalWidth / 2, startY = -200;
    
    FOOD_ITEMS.forEach((item, index) => {
        const col = index % cols, row = Math.floor(index / cols);
        const x = startX + col * (cardWidth + gapX);
        const y = startY + row * (cardHeight + gapY);
        const card = createItemCard(scene, item, x, y, cardWidth, cardHeight, 'food');
        modal.add(card);
    });
    
    const dividerY = startY + Math.ceil(FOOD_ITEMS.length / cols) * (cardHeight + gapY) + 15;
    const divider = scene.add.graphics();
    divider.lineStyle(1, 0x4fc3f7, 0.3);
    divider.lineBetween(-380, dividerY, 380, dividerY);
    modal.add(divider);
    
    const medTitle = scene.add.text(0, dividerY + 15, '💊 Медикаменты', { fontSize: '14px', fontFamily: 'Russo One, Arial', color: '#e74c3c' });
    medTitle.setOrigin(0.5);
    modal.add(medTitle);
    
    const medStartY = dividerY + 40;
    MEDICINE_ITEMS.forEach((item, index) => {
        const col = index % cols, row = Math.floor(index / cols);
        const x = startX + col * (cardWidth + gapX);
        const y = medStartY + row * (cardHeight + gapY);
        const card = createItemCard(scene, item, x, y, cardWidth, cardHeight, 'medicine');
        modal.add(card);
    });
}

function createItemCard(scene, item, x, y, width, height, category) {
    const card = scene.add.container(x, y);
    const isPurchased = gameState[category].includes(item.id);
    
    const bg = scene.add.graphics();
    bg.fillStyle(isPurchased ? 0x27ae60 : 0x2a2a4a, 1);
    bg.fillRoundedRect(0, 0, width, height, 6);
    card.add(bg);
    
    const icon = scene.add.text(12, height/2, item.icon, { fontSize: '20px' });
    icon.setOrigin(0, 0.5);
    card.add(icon);
    
    const name = scene.add.text(45, 8, item.name, { fontSize: '12px', fontFamily: 'Nunito, Arial', color: '#ffffff', fontStyle: 'bold' });
    card.add(name);
    
    const effect = scene.add.text(45, 26, item.effect, { fontSize: '10px', fontFamily: 'Nunito, Arial', color: '#aaaaaa' });
    card.add(effect);
    
    const priceColor = gameState.money >= item.price ? '#f1c40f' : '#e74c3c';
    const info = scene.add.text(width - 90, height/2, `${formatMoney(item.price)} • ${item.weight}кг`, { fontSize: '10px', fontFamily: 'Nunito, Arial', color: priceColor });
    info.setOrigin(0.5);
    card.add(info);
    
    const btnX = width - 35, btnY = 8, btnW = 28, btnH = height - 16;
    const btnColor = isPurchased ? 0xe74c3c : 0x3498db;
    const btnText = isPurchased ? '✕' : '+';
    
    const btn = scene.add.graphics();
    btn.fillStyle(btnColor, 1);
    btn.fillRoundedRect(btnX, btnY, btnW, btnH, 5);
    card.add(btn);
    
    const btnLabel = scene.add.text(btnX + btnW/2, btnY + btnH/2, btnText, { fontSize: '16px', fontFamily: 'Nunito, Arial', color: '#ffffff', fontStyle: 'bold' });
    btnLabel.setOrigin(0.5);
    card.add(btnLabel);
    
    const btnHit = scene.add.zone(btnX + btnW/2, btnY + btnH/2, btnW, btnH);
    btnHit.setInteractive({ useHandCursor: true });
    btnHit.on('pointerdown', () => toggleItem(scene, item, category));
    card.add(btnHit);
    
    return card;
}

async function toggleItem(scene, item, category) {
    const isPurchased = gameState[category].includes(item.id);
    
    try {
        if (isPurchased) {
            const result = await apiRequest(API_ROUTES.sellEquipment, 'POST', { item_code: item.id });
            showNotification(result.message, 'warning');
        } else {
            const result = await apiRequest(API_ROUTES.buyEquipment, 'POST', { item_code: item.id });
            showNotification(result.message, 'success');
        }
        closeModal(scene, currentModal);
        setTimeout(() => { if (category === 'equipment') openEquipmentModal(scene); else openFoodModal(scene); }, 200);
    } catch (error) {}
}

// ═══════════════════════════════════════════════════════════════════════════
// ОКНО ПОДРАБОТОК
// ═══════════════════════════════════════════════════════════════════════════

function openJobsModal(scene) {
    const modal = createModal(scene, '💼 ДОСКА ОБЪЯВЛЕНИЙ', 900, 580);
    
    const cardWidth = 820, cardHeight = 85, gapY = 8;
    const startX = -cardWidth / 2, startY = -200;
    
    JOB_OFFERS.forEach((job, index) => {
        const y = startY + index * (cardHeight + gapY);
        const card = createJobCard(scene, job, startX, y, cardWidth, cardHeight);
        modal.add(card);
    });
}

function createJobCard(scene, job, x, y, width, height) {
    const card = scene.add.container(x, y);
    const isAccepted = gameState.jobs.includes(job.id);
    const riskColors = { 1: 0x27ae60, 2: 0xf39c12, 3: 0xe74c3c };
    
    const bg = scene.add.graphics();
    bg.fillStyle(isAccepted ? 0x27ae60 : 0x2a2a4a, 1);
    bg.fillRoundedRect(0, 0, width, height, 8);
    bg.lineStyle(2, riskColors[job.riskLevel] || 0x4fc3f7, 0.5);
    bg.strokeRoundedRect(0, 0, width, height, 8);
    card.add(bg);
    
    const icon = scene.add.text(20, height/2, job.icon, { fontSize: '28px' });
    icon.setOrigin(0, 0.5);
    card.add(icon);
    
    const title = scene.add.text(65, 12, job.title, { fontSize: '14px', fontFamily: 'Nunito, Arial', color: '#ffffff', fontStyle: 'bold' });
    card.add(title);
    
    const descText = job.description.length > 55 ? job.description.substring(0, 55) + '...' : job.description;
    const desc = scene.add.text(65, 32, descText, { fontSize: '10px', fontFamily: 'Nunito, Arial', color: '#aaaaaa' });
    card.add(desc);
    
    const riskTextColors = { 1: '#27ae60', 2: '#f39c12', 3: '#e74c3c' };
    const infoText = `💰 ${formatMoney(job.reward)} | ⚠️ ${job.risk} | 📦 ${job.cargoWeight}кг | ${job.duration}`;
    const info = scene.add.text(65, 55, infoText, { fontSize: '11px', fontFamily: 'Nunito, Arial', color: riskTextColors[job.riskLevel] || '#ffffff' });
    card.add(info);
    
    const btnX = width - 95, btnY = 25, btnW = 80, btnH = 35;
    const btnColor = isAccepted ? 0xe74c3c : 0x3498db;
    const btnText = isAccepted ? 'Отмена' : 'Принять';
    
    const btn = scene.add.graphics();
    btn.fillStyle(btnColor, 1);
    btn.fillRoundedRect(btnX, btnY, btnW, btnH, 6);
    card.add(btn);
    
    const btnLabel = scene.add.text(btnX + btnW/2, btnY + btnH/2, btnText, { fontSize: '12px', fontFamily: 'Nunito, Arial', color: '#ffffff', fontStyle: 'bold' });
    btnLabel.setOrigin(0.5);
    card.add(btnLabel);
    
    const btnHit = scene.add.zone(btnX + btnW/2, btnY + btnH/2, btnW, btnH);
    btnHit.setInteractive({ useHandCursor: true });
    btnHit.on('pointerdown', () => toggleJob(scene, job));
    card.add(btnHit);
    
    return card;
}

async function toggleJob(scene, job) {
    const isAccepted = gameState.jobs.includes(job.id);
    
    try {
        if (isAccepted) {
            const result = await apiRequest(API_ROUTES.cancelJob, 'POST', { job_code: job.id });
            showNotification(result.message, 'warning');
        } else {
            const result = await apiRequest(API_ROUTES.acceptJob, 'POST', { job_code: job.id });
            showNotification(result.message, 'success');
        }
        closeModal(scene, currentModal);
        setTimeout(() => openJobsModal(scene), 200);
    } catch (error) {}
}

// ═══════════════════════════════════════════════════════════════════════════
// ПРОВЕРКА ГОТОВНОСТИ
// ═══════════════════════════════════════════════════════════════════════════

async function checkReadyToDepart(scene) {
    try {
        const result = await apiRequest(API_ROUTES.readyToDepart);
        if (result.ready) {
            showDepartureModal(scene);
        } else {
            showProblemModal(scene, result.problems);
        }
    } catch (error) {}
}

function showProblemModal(scene, problems) {
    const modal = createModal(scene, '⚠️ НЕ ГОТОВЫ К ОТПЛЫТИЮ', 600, 400);
    
    const intro = scene.add.text(0, -120, 'Для отплытия необходимо:', { fontSize: '16px', fontFamily: 'Nunito, Arial', color: '#ffffff' });
    intro.setOrigin(0.5);
    modal.add(intro);
    
    problems.forEach((problem, index) => {
        const text = scene.add.text(-250, -70 + index * 35, `❌ ${problem}`, { fontSize: '14px', fontFamily: 'Nunito, Arial', color: '#e74c3c' });
        modal.add(text);
    });
    
    const btnBg = scene.add.graphics();
    btnBg.fillStyle(0x3498db, 1);
    btnBg.fillRoundedRect(-60, 120, 120, 40, 10);
    modal.add(btnBg);
    
    const btnText = scene.add.text(0, 140, 'Понятно', { fontSize: '16px', fontFamily: 'Nunito, Arial', color: '#ffffff', fontStyle: 'bold' });
    btnText.setOrigin(0.5);
    modal.add(btnText);
    
    const btnHit = scene.add.zone(0, 140, 120, 40);
    btnHit.setInteractive({ useHandCursor: true });
    btnHit.on('pointerdown', () => closeModal(scene, modal));
    modal.add(btnHit);
}

function showDepartureModal(scene) {
    const modal = createModal(scene, '🚢 ГОТОВЫ К ОТПЛЫТИЮ!', 700, 500);
    
    const summary = [
        `💰 Бюджет: ${formatMoney(gameState.money)}`,
        `🧑‍✈️ Экипаж: ${gameState.crew.length} человек`,
        `🍞 Продовольствие: на ${gameState.foodDays} дней`,
        `⚖️ Загрузка: ${gameState.cargoUsed}/${MAX_CARGO_CAPACITY} кг`,
        `💼 Подработки: ${gameState.jobs.length}`
    ];
    
    const expectedReward = JOB_OFFERS.filter(j => gameState.jobs.includes(j.id)).reduce((sum, j) => sum + j.reward, 0);
    if (expectedReward > 0) summary.push(`💵 Ожидаемый заработок: ${formatMoney(expectedReward)}`);
    
    summary.forEach((line, index) => {
        const text = scene.add.text(-200, -120 + index * 35, line, { fontSize: '16px', fontFamily: 'Nunito, Arial', color: '#ffffff' });
        modal.add(text);
    });
    
    const btnBg = scene.add.graphics();
    btnBg.fillStyle(0x27ae60, 1);
    btnBg.fillRoundedRect(-100, 130, 200, 50, 15);
    modal.add(btnBg);
    
    const btnText = scene.add.text(0, 155, '⛵ ОТПЛЫТЬ!', { fontSize: '20px', fontFamily: 'Russo One, Arial', color: '#ffffff' });
    btnText.setOrigin(0.5);
    modal.add(btnText);
    
    const btnHit = scene.add.zone(0, 155, 200, 50);
    btnHit.setInteractive({ useHandCursor: true });
    btnHit.on('pointerdown', async () => {
        closeModal(scene, modal);
        try {
            const result = await apiRequest(API_ROUTES.depart, 'POST');
            showNotification(result.message, 'success');
            setTimeout(() => { window.location.href = result.redirect; }, 1500);
        } catch (error) {}
    });
    modal.add(btnHit);
}
