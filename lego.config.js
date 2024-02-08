export default {
    sourceDir: './client-components/bricks',
    targetDir: './client-components/dist',
    useShadowDOM: false,
    preScript: ` 
    import { render } from './lego.min.js';
   
    Component.prototype.render = function(state)
    {
      const childs = Array.from(this.children);
      this.__originalChildren = childs.length && !this.__originalChildren?.length ? childs : this.__originalChildren;

       this.__state.slotId = \`slot_\${performance.now().toString().replace('.','')}_\${Math.floor(Math.random() * 1000)}\`;
   
      this.setState(state);
      if(!this.__isConnected) return
   
      const rendered = render([
        this.vdom({ state: this.__state }),
        this.vstyle({ state: this.__state }),
      ], this.document);
   
      const slot = this.document.querySelector(\`#\${this.__state.slotId}\`);
      if (slot)
         for (const c of this.__originalChildren)
             slot.appendChild(c);
            
      return rendered;
    };`
    //preStyle: fs.readFileSync('./assets/twoutput.css', { encoding: "utf8" })
};