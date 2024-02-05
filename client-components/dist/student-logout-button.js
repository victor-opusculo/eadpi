 // Lego version 1.0.0
  import { h, Component } from './lego.min.js'
   
    import { render } from './lego.min.js';
   
    Component.prototype.render = function(state)
    {
       const childrenHtml = Array.from(this.children);
       this.__state.slotId = `slot_${performance.now().toString().replace('.','')}_${Math.floor(Math.random() * 1000)}`;
   
      this.setState(state);
      if(!this.__isConnected) return
   
      const rendered = render([
        this.vdom({ state: this.__state }),
        this.vstyle({ state: this.__state }),
      ], this.document);
   
      const slot = this.document.querySelector(`#${this.__state.slotId}`);
      if (slot)
         for (const c of childrenHtml)
             slot.appendChild(c);
            
      return rendered;
    };

  
    const methods =
    {
        logout()
        {
            fetch(EADPI.Helpers.URLGenerator.generateApiUrl('/student/logout'))
            .then(res => res.json())
            .then(json => 
            {
                EADPI.Alerts.pushFromJsonResult(json);
                if (json.success)
                    window.location.href = EADPI.Helpers.URLGenerator.generatePageUrl('/students/login');
            })
            .catch(reason => EADPI.Alerts.push(EADPI.Alerts.types.error, String(reason)));
        }
    };



  const __template = function({ state }) {
    return [  
    h("button", {"class": `btn`, "onclick": this.logout.bind(this), "type": `button`}, `Sair`)
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

  
