function clearPlaylist()
{
    var itemId=this.tree2.rootId;
    var temp=this.tree2._globalIdStorageFind(itemId);
    this.tree2.deleteChildItems(itemId);
}
function setValue()
{
    var i = 0;
    var j = 0;
    var n = 0;
    arvArray = new Array();
    arvArray = getChilds(this.tree2.htmlNode, arvArray, "/")
    var arv = arvArray.toString();
    document.treeform.arv.value = escape(arv);
}
function getChilds(Childs, arr, label) {
    var i = 0;
    for(i = 0; i < Childs.childsCount; i++) {
        if(Childs.childNodes[i].childsCount == 0) {
            if(Childs.childNodes[i].label[0] != "/") {
                arr.push(label+Childs.childNodes[i].label);
            }
            else arr.push(Childs.childNodes[i].label);
        }
        else {
            arr = getChilds(Childs.childNodes[i], arr, label+Childs.childNodes[i].label+"/")
        }
    }
    return arr;
}