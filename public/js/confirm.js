function deleteHandle(event) {
    // 一旦フォームの動作をストップ
    event.preventDefault();
    if (window.confirm("本当に削除してもよろしいですか？")) {
        // 削除OKなら再開
        document.getElementById('delete-form').submit();
    } else {
        alert('キャンセルしました。');
    }
}