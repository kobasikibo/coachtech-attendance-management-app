document.addEventListener("DOMContentLoaded", function () {
    function normalizeTime(input, forceFormat = false) {
        let mapping = {
            '０': '0', '１': '1', '２': '2', '３': '3', '４': '4',
            '５': '5', '６': '6', '７': '7', '８': '8', '９': '9',
            '一': '1', '二': '2', '三': '3', '四': '4', '五': '5',
            '六': '6', '七': '7', '八': '8', '九': '9', '十': '10',
            '：': ':'};

        let value = input.value.trim().replace(/[０-９一二三四五六七八九十：]/g, m => mapping[m] || m);
        value = value.replace(/時半/, ':30').replace(/時/, ':00');

        // フォーマット適用は blur 時のみ
        if (forceFormat) {
            let match = value.match(/^(\d{1,2}):?(\d{0,2})$/);
            if (match) {
                let [hour, minute = '00'] = value.split(":");
                input.value = hour.padStart(2, '0') + ":" + minute.padEnd(2, '0');
            }
        } else {
            input.value = value; // 入力中は全角→半角変換のみ適用
        }
    }

    document.querySelectorAll("input[name='clock_in'], input[name='clock_out'], input[name*='break_start'], input[name*='break_end']")
        .forEach(input => {
            input.addEventListener("input", () => normalizeTime(input)); // 入力中は変換のみ
            input.addEventListener("blur", () => normalizeTime(input, true)); // フォーカスが外れたらフォーマット適用
        });
});