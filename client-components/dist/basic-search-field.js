 // Lego version 1.0.0
  import { h, Component } from './lego.min.js'
   
    import { render } from './lego.min.js';
   
    Component.prototype.render = function(state)
    {
      const childs = Array.from(this.childNodes);
      this.__originalChildren = childs.length && !this.__originalChildren?.length ? childs : this.__originalChildren;

       this.__state.slotId = `slot_${performance.now().toString().replace('.','')}_${Math.floor(Math.random() * 1000)}`;
   
      this.setState(state);
      if(!this.__isConnected) return
   
      const rendered = render([
        this.vdom({ state: this.__state }),
        this.vstyle({ state: this.__state }),
      ], this.document);
   
      const slot = this.document.querySelector(`#${this.__state.slotId}`);
      if (slot)
         for (const c of this.__originalChildren)
             slot.appendChild(c);
            
      return rendered;
    };

  
    const state =
    {
        searchkeywords: '',
        searchcallback: null
    };

    const methods =
    {
        changeInput(e)
        {
            this.render({ ...this.state, searchkeywords: e.target.value });
        },

        keydown(e)
        {
            if (e.keyCode === 13)
            {
                e.preventDefault();
                this.buttonClicked();
            }
        },

        buttonClicked(e)
        {
            if (typeof this.state.searchcallback === "function")
                this.state.searchcallback(this.state.searchkeywords);
        }
    };


  const __template = function({ state }) {
    return [  
    h("span", {"class": `flex flex-row items-center`}, [
      h("label", {}, [
`
            Pesquisar:
            `,
        h("input", {"type": `search`, "value": state.searchkeywords, "oninput": this.changeInput.bind(this), "onkeydown": this.keydown.bind(this)}, "")
      ]),
      h("button", {"type": `button`, "class": `btn ml-2 min-w-[32px]`, "onclick": this.buttonClicked.bind(this)}, [
        h("img", {"src": `${EADPI.Helpers.URLGenerator.generateFileUrl('assets/pics/search.png')}`, "alt": `Pesquisar`, "width": `28`}, "")
      ])
    ])
  ]
  }

  const __style = function({ state }) {
    return h('style', {}, `
      
      
    `)
  }

  // -- Lego Core
  export default class Lego extends Component {
    init() {
      this.useShadowDOM = false
      if(typeof state === 'object') this.__state = Object.assign({}, state, this.__state)
      if(typeof methods === 'object') Object.keys(methods).forEach(methodName => this[methodName] = methods[methodName])
      if(typeof connected === 'function') this.connected = connected
      if(typeof setup === 'function') setup.bind(this)()
    }
    get vdom() { return __template }
    get vstyle() { return __style }
  }
  // -- End Lego Core

  
