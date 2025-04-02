const highlightedDates = ["2025-04-02", "2025-12-25"]; // Example dates
let currentDate = new Date();

function renderCalendar(date) {
    const calendar = document.getElementById("calendar");
    calendar.innerHTML = "";

    const monthYear = document.getElementById("month-year");
    monthYear.textContent = date.toLocaleString("default", { month: "long", year: "numeric" });

    const firstDay = new Date(date.getFullYear(), date.getMonth(), 1).getDay();
    const daysInMonth = new Date(date.getFullYear(), date.getMonth() + 1, 0).getDate();

    const daysOfWeek = ["Sun", "Mon", "Tue", "Wed", "Thu", "Fri", "Sat"];
    daysOfWeek.forEach(day => {
        const dayElement = document.createElement("div");
        dayElement.textContent = day;
        dayElement.style.fontWeight = "bold";
        calendar.appendChild(dayElement);
    });

    for (let i = 0; i < firstDay; i++) {
        const emptyDiv = document.createElement("div");
        emptyDiv.classList.add("day");
        calendar.appendChild(emptyDiv);
    }

    for (let day = 1; day <= daysInMonth; day++) {
        const dateStr = `${date.getFullYear()}-${String(date.getMonth() + 1).padStart(2, '0')}-${String(day).padStart(2, '0')}`;
        const dayElement = document.createElement("div");
        dayElement.textContent = day;
        dayElement.classList.add("day");
        dayElement.setAttribute("data-date", dateStr);  // Add unique data attribute

        if (new Date().toDateString() === new Date(date.getFullYear(), date.getMonth(), day).toDateString()) {
            dayElement.classList.add("highlight-today");
        }
        if (highlightedDates.includes(dateStr)) {
            dayElement.classList.add("highlight-date");
        }

        calendar.appendChild(dayElement);
    }

    // Fetch user-registered events and highlight them
    fetchUserEvents();
}

function prevMonth() {
    currentDate.setMonth(currentDate.getMonth() - 1);
    renderCalendar(currentDate);
}

function nextMonth() {
    currentDate.setMonth(currentDate.getMonth() + 1);
    renderCalendar(currentDate);
}

function fetchUserEvents() {
    fetch('fetch_events.php')
        .then(response => response.json())
        .then(events => {
            Object.keys(events).forEach(date => {
                const dayElement = document.querySelector(`[data-date="${date}"]`);
                if (dayElement) {
                    dayElement.classList.add("highlight-user-event");
                    dayElement.setAttribute("title", events[date]); // Tooltip for event name
                }
            });
        })
        .catch(error => console.error("Error fetching events:", error));
}

document.addEventListener("DOMContentLoaded", () => renderCalendar(currentDate));
