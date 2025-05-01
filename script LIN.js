function updateEthiopianClock() {
    const now = new Date();
    let hours = now.getHours() - 6;
    if (hours < 0) hours += 12;
    let minutes = now.getMinutes().toString().padStart(2, '0');
    document.getElementById('ethiopian-clock').innerText = `ሰዓት: ${hours}:${minutes}`;
  }
  setInterval(updateEthiopianClock, 1000);
  window.onload = updateEthiopianClock;
  