(define-record start (List))
(define-record eventDefinition (Token1 Token2 Token3
   condition Token4))
(define-record startDefinition (Token1 Token2 condition
   Optional Token3))
(define-record opDefinition (Token1 Token2 Token3
   Token4 Token5 Token6 Token7
   Token8 Token9 listaParam1 Token10
   Token11 listaParam2 Token12 Token13
   Token14 Token15 Token16 Token17
   Token18))
(define-record listaParam (Token Optional1 Optional2
   List))
(define-record outputFilter (Token1 Optional listaParam
   Token2))
(define-record lista (Token1 condition List
   Token2))
(define-record namespace (Token1 Token2 Token3
   Token4))
