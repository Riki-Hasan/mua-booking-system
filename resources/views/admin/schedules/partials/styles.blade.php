<style>
    /* CSS Hapus Spinner Input Angka */
    input::-webkit-outer-spin-button, input::-webkit-inner-spin-button { -webkit-appearance: none; margin: 0; }
    input[type=number] { -moz-appearance: textfield; }
    input[type=number]:focus { scroll-behavior: contain; }

    /* Grid Form - Responsive Layout */
    .grid-form-parent { display: grid; grid-template-columns: repeat(6, 1fr); gap: 1.25rem; }
    .div1, .div2, .div3, .div4, .div5, .div6, .div7, .div8, .div9 { grid-column: span 6; }

    @media (min-width: 768px) {
        .div1 { grid-column: span 3; }
        .div2 { grid-column: span 3; }
        .div3 { grid-column: span 4; }
        .div4 { grid-column: span 2; }
        .div5 { grid-column: span 3; }
        .div6 { grid-column: span 3; }
    }
    
    .select-wrapper { position: relative; width: 100%; display: flex; align-items: center; }
    .select-wrapper::after { content: '▼'; font-size: 10px; position: absolute; right: 15px; pointer-events: none; color: #4b5563; }
    
    /* REVISI KETERBACAAN INPUT FORM KHUSUS TABLET JADUL */
    input[type="text"], input[type="number"], input[type="time"], select {
        -webkit-appearance: none; 
        appearance: none; 
        background: #ffffff !important; 
        border: 2px solid #cbd5e1 !important; 
        width: 100%; 
        cursor: pointer; 
        border-radius: 1rem;
        color: #0f172a !important;
        font-weight: 700 !important;
        font-size: 14px !important;
    }
    label {
        font-size: 12px !important;
        font-weight: 900 !important;
        color: #374151 !important;
        text-transform: uppercase !important;
        letter-spacing: 0.05em !important;
        margin-bottom: 6px !important;
    }
    
    .day-btn { aspect-ratio: 1 / 1; display: flex; align-items: center; justify-content: center; font-size: 0.875rem; border-radius: 1rem; font-weight: 800; transition: all 0.3s; border: none; }
    .holiday-active { background-color: #e11d48 !important; color: white !important; }
    .before-today { opacity: 0.25; cursor: not-allowed; background-color: #f3f4f6; color: #9ca3af; }
    .booking-partial { background-color: #d97706 !important; color: white !important; }
    .active-date { background-color: #db2777 !important; color: white !important; ring: 4px; ring-color: rgba(219, 39, 119, 0.4); }
    
    input[type="time"]::-webkit-calendar-picker-indicator { filter: invert(0); cursor: pointer; transform: scale(1.3); }
    
    .summary-parent { display: grid; grid-template-columns: repeat(2, 1fr); gap: 1rem; }
    .s-div1 { grid-column: span 2; } .s-div2 { grid-column: span 1; } .s-div3 { grid-column: span 1; }
    .s-div4 { grid-column: span 2; } .s-div5 { grid-column: span 2; }
    @media (min-width: 640px) {
        .s-div4 { grid-column: span 1; } .s-div5 { grid-column: span 1; }
    }

    /* PAKSA OVERLAY MODAL TETAP DI TENGAH PADA TABLET */
    #statusModal, #confirmModal {
        position: fixed !important;
        top: 0 !important;
        left: 0 !important;
        right: 0 !important;
        bottom: 0 !important;
        width: 100% !important;
        height: 100% !important;
        background-color: rgba(15, 23, 42, 0.8) !important;
        align-items: center !important;
        justify-content: center !important;
        z-index: 2147483647 !important;
    }
</style>