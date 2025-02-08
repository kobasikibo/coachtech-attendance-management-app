function updateTime() {
    const now = new Date();
    const formattedTime = now.toLocaleTimeString('ja-JP', {
        hour: '2-digit',
        minute: '2-digit',
    });

    const elements = document.getElementsByClassName('current-time');
    for (const element of elements) {
        element.textContent = formattedTime;
    }
}

updateTime(); // 初回実行
setInterval(updateTime, 1000); // 1秒ごとに更新