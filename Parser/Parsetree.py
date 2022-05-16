#COMP 5361 A2, Xiang Chen Zhu, ID:40216952
import sys
'''Input format:
- Propositional assignments: Each variable/operator has to be separated by white spaces before and after.
- Truth assignments: In the form of Pn=true,pn=f,etc. Separated by white spaces
- Uppercases are handled, namely, p1 and P1 are the same thing. T an t both stands for true.
- Have to run under Python >=3.8
'''

'''List of valid syntax
Default format:
and - &
or - |
implies - >
not - !
'''

and_syntax = ('^','&&','∧')
or_syntax = ('||','∨')
not_syn =('~','¬')
implies_syntax =('=>','->','⇒','→')
formal_syntax = ('(',')','&','|','!','>')


OPMAP={**dict.fromkeys(and_syntax,'&'),**dict.fromkeys(or_syntax,'|'),**dict.fromkeys(not_syn,'!'),**dict.fromkeys(implies_syntax,'>')}
VALIDSYNTAX=set(and_syntax+or_syntax+not_syn+implies_syntax+formal_syntax) 
APPENDABLE_SYNTAX_LEFT = set(('!','('))
APPENDABLE_SYNTAX_RIGHT = set(formal_syntax).difference(APPENDABLE_SYNTAX_LEFT)
BOOLSYNTAX={'TRUE':True,'T':True,'FALSE':False,'F':False}
PRIORITY={'(':1,'&':2,'|':2,'>':2,'!':3}
''''''
class InvalidExpression(Exception):
    def __init__(self, message):
        super().__init__()
        self.message = message

class InvalidTruth(Exception):
    def __init__(self, message):
        super().__init__()
        self.message = message

class EmptyTruth(Exception):
    def __init__(self, message):
        super().__init__()
        self.message = message

class InvalidFileFormat(Exception):
    def __init__(self, message):
        super().__init__()
        self.message = message

class TreeNode:
    def __init__(self,data,str,left=None,right=None):
        self.data = data
        self.str = str #Part 2
        self.left = left
        self.right = right

class Var_Node(TreeNode):
    def __init__(self, data, str, left=None, right=None):
        super().__init__(data, str, left=left, right=right)

    def evaluate(self):
        return self.data
    
    def getstr(self):
        return self.str

    def get_names_list(self,l) -> list: #part 2
        l.append(self.str)
        return l

    def evaluate_tuple(self,l) -> tuple: # part 2
        temp = self.evaluate()
        return (temp,l)

class Op_Node(TreeNode):
    def __init__(self, data,left=None, right=None):
        self.data = data
        self.left = left
        self.right = right
        temp = ''
        # if(left):
        #     print(left.getstr())
        # print(right.getstr())
        # print(data)
        if (data == '!'):
            temp = '! '+right.getstr()
        else:
            temp = '('+left.getstr()+' '+data+ ' '+right.getstr()+')'
        self.str = temp
    
    def getstr(self):
        return self.str
    
    def evaluate(self):
        rightbool = self.right.evaluate()
        if self.data == '!':
            return not rightbool
        leftbool = self.left.evaluate()
        if(self.data == '&'):
            return (leftbool and rightbool)
        elif(self.data == '|'):
            return (leftbool or rightbool)
        # elif(self.data == '!'):
        #     return not rightbool
        elif(self.data == '>'):
            if leftbool is True and rightbool is False:
                return False
            return True
        else:
            pass

    def evaluate_tuple(self, l) -> tuple: #part2
        rightbool = self.right.evaluate_tuple(l)[0]
        if self.data == '!':
            temp = not rightbool
            l.append(temp)
            return (temp,l)
        leftbool = self.left.evaluate_tuple(l)[0]
        if(self.data == '&'):
            temp = leftbool and rightbool
            l.append(temp)
            return (temp,l)
        elif(self.data == '|'):
            temp = leftbool or rightbool
            l.append(temp)
            return (temp,l)
        # elif(self.data == '!'):
        #     return not rightbool
        elif(self.data == '>'):
            if leftbool is True and rightbool is False:
                temp = False
                l.append(temp)
                return (temp,l)
            temp= True
            l.append(True)
            return (temp,l)
        else:
            pass

    def get_names_list(self,l) -> list: #part 2
        if self.right is not None:
            if isinstance(self.right, Op_Node):
                self.right.get_names_list(l)
        if self.left is not None:
            if isinstance(self.left, Op_Node):
                self.left.get_names_list(l)
        l.append(self.str)
        return l
    

def get_num_var(truth_assignment):
    return len(truth_assignment)

def isValidparentheses(expr):
    stack =[]
    for c in expr:
        if c == '(':
            stack.append(c)
        elif c == ')':
            if not stack:
                return False
            stack.pop()
    if stack:
        return False
    return True
         

def isValidvar(variable):
    if variable.upper() in BOOLSYNTAX:
        return True
    elif len(variable) > 1:
        if variable[0].upper() == 'P':
            if all(s.isdigit() for s in variable[1:] ):
                if int(variable[1]):
                    return True
    return False

def isValidop(token): #Some recursion
    if len(token) == 1:
        return token in formal_syntax
    elif len(token) > 1:
        if token[0] in formal_syntax:
            if token[0] == ')':
                if token[1] in APPENDABLE_SYNTAX_RIGHT:
                    return isValidop(token[1:])
                else:
                    return False
            else:
                return all(c in APPENDABLE_SYNTAX_LEFT for c in token[1:])
        else:
            return False
    else:
        print("Empty token")
        return





def isValidtruth_asg(ta):
    if not ta:
        print("Empty truth assignment!")
        return
    if not all(isValidvar(x:=var)  for var in ta.keys()):
        print(f"Invalid variable {x} . It has to be in the form of 'Pn'.")
        return False
    # if not all((y:=val).upper() in BOOLSYNTAX for val in ta.values()):
    if not all((y:= val) in (True,False) for val in ta.values()):
        print(f"Invalid truth value {y} . It has to be T or F.")
        return False
    return True




def ifbool_tax(token):
    if token.upper() in BOOLSYNTAX:
        return True
    return False
   
def assign_var(truth,variable): #True, False, or None --- None leads to the session being aborted
    #P1=True, P2= True  P1 and P2 or P3
    value = truth.get(variable)
    if value is not None:
        return value
    else:
        print(f"{variable} has no corresponding truth assignment.\nEnter T to assign True or F to assign False to it.\nOr A to abort the current session.")
        while True:
            temp = input(">>")
            if temp.lower()== 't':
                print(f'{variable} is True now .\n')
                truth[variable]= True #Add correspoding variable to dictionary
                return True
            elif temp.lower()== 'f':
                print(f'{variable} is False now .\n')
                truth[variable]= False
                return False
            elif temp.lower()== 'a':
                print("Session aborted\n")
                return
            else:
                print("Please enter a valid input: T/F/A\n")

def tokenize(expression): #Convert the input string to a list of tokens, perform validation
    if not expression:
        print("\nExpression empty.")
        raise InvalidExpression("EMPTY")
    if not isValidparentheses(expression):
        print("\nThe parentheses are not balanced. Please check your expression again.")
        raise InvalidExpression(expression)
    toreturn = []
    prev = '('
    any_var = False
    l_exp = expression.split()
    '''
    Three cases depending on the previous token:

    if var -> no '(' or var or '!'
    if ')' -> no var, and has to satisify the operator constraint when ) is added to current operator
    else ->  Same constraint as above
    '''
    for item in l_exp:
        if prev[0].upper() in ('P','T','F'):
            if isValidvar(item):
                print("Two consecutive variables without operators.")
                raise InvalidExpression(prev+" "+item)
            elif isValidop(item):
                if item[0] in APPENDABLE_SYNTAX_LEFT :
                    print("Invalid operator(s) following a variable.")
                    raise InvalidExpression(prev+" "+item)
                toreturn.extend(item)
                prev = toreturn[-1]
            else:
                raise InvalidExpression(item)
        elif prev ==')':
            if isValidvar(item):
                print("Immediate variable following right parentheses.") 
                raise InvalidExpression(prev + "" + item)
            elif isValidop(item):
                if not isValidop(prev+item[0]):
                    raise InvalidExpression(prev + "" + item)
                toreturn.extend(item)
                prev = toreturn[-1]
            else:
                raise InvalidExpression(item)
        else:
            if isValidvar(item):
                any_var =True
                toreturn.append(item)
                prev = toreturn[-1]
            elif isValidop(item):
                if not isValidop(prev+item[0]):
                    raise InvalidExpression(prev + "" + item)
                toreturn.extend(item)
                prev = toreturn[-1]
            else:
                raise InvalidExpression(item)


    if not any_var:
        print("\nNo propositional variables present. Are you missing them?")
        raise InvalidExpression(expression)
    return toreturn

def get_var_token(l):
    return set(t.upper() for t in l if t.isalnum() and t[0].upper() == 'P')

def node_op_stack(op,tree):
    operator = op.pop()
    right = tree.pop()
    if(operator =='!'):
        node = Op_Node(operator,right=right)
    else:
        left = tree.pop()
        node = Op_Node(operator,left,right)
    tree.append(node)

def expTOtree(assignment,expression):
    l_exp = tokenize(expression)
    b_1 = isValidtruth_asg(assignment)
    if b_1 is not None:
        if not b_1:
            raise InvalidTruth(str(assignment))
    else:
        raise EmptyTruth("Empty truth")
    
    # print(l_exp)
    assignment.update(BOOLSYNTAX) # 'TRUE' = true, 'False' = false
    tree_s = []   # operand stack
    operator_s =[] # operator stack
    #operand_stack ( True or false value)= [Node(T),Node(T)] pop() popagain()                   [Node(&,T,T),Node(F)] pop pop again
    #operator_stack(operator)  = [&] -> pop()  Node(&,RightChild=T,LeftChild=T)   [|]  pop  Node(|,Node(F),Node(&,T,T))

    ###     |
    ###  &     F
    ### T T
    for item in l_exp:
        # print(item)
        # print(operator_s)
        if item == '(':
            operator_s.append(item)
        elif item.isalnum():
            item_value = assign_var(assignment,item.upper()) # Found corresponding boolean in the dictionary
            if item_value is not None:
                node = Var_Node(item_value,item)  # Create a node
                tree_s.append(node)  # push to operand stack
            else:
                print(f"{item} has no corresponding value in the truth assignment.")
                raise InvalidTruth(item)
        elif item == ')':
            while operator_s[-1] != '(':
                node_op_stack(operator_s,tree_s)
            operator_s.pop()
        else:
            while len(operator_s) > 0 and ((item != '!' and PRIORITY[operator_s[-1]] >=  PRIORITY[item]) or (item == '!' and PRIORITY[operator_s[-1]] >  PRIORITY[item])):
                node_op_stack(operator_s,tree_s)
            operator_s.append(item)

    # print("The node on top of tree stack is"+tree_s[-1].str)
    # for item in tree_s:
    #     print(item.str)
    # print(operator_s)
    while len(tree_s) > 1:
        node_op_stack(operator_s,tree_s)

    while(operator_s and operator_s[-1] == '!'):
        node_op_stack(operator_s,tree_s)

    format_str = tree_s[-1].str
    if format_str[0] == '(':
        tree_s[-1].str = tree_s[-1].str[1:-1]
    return tree_s[-1]

def printtree(node,level =0):
    if node != None:
        printtree(node.right,level+1)
        print(' ' * 4 * level + '->', node.data)
        printtree(node.left, level + 1)

def tt_generator (n):
  if n < 1:
    return [[]]
  t_sub = tt_generator(n-1)
  return [ row + [b] for row in t_sub for b in [True,False] ] 

def dict_l_generator(l_variable):
    d =[]
    tt = tt_generator(len(l_variable))
    for t_value in tt:
        d.append(dict(zip(l_variable,t_value))) 
    return d

def print_table(names, values):
    row_format =""
    for name in names:
        row_format +="{!s:^" + str(4+len(name))+"}"
    # print(row_format)
    print(row_format.format(*names))

    for row in values:
        print(row_format.format(*row))

def tt_determine(expr,variables) -> str:
    expressions = []
    data = []
    variable =[]
    b_result=[]
    get_names = True
    variable.extend(sorted(variables))
    # print(variable)
    expressions.extend(variable)
    test_dict = dict_l_generator(variable)
    for d in test_dict: #Every assignment in the truth table
        temp_data = list(d.values())
        tree = expTOtree(d,expr)
        if(get_names):
            expressions.extend(tree.get_names_list([]))
            get_names = False
        t = tree.evaluate_tuple([])
        # print(type(tree))
        if isinstance(tree,Op_Node):
            temp_data.extend(t[1])
        else:
            temp_data.extend([t[0]])
        # print(temp_data)
        b_result.append(t[0])
        data.append(temp_data)
        # print(data)
    # print(test_dict)
    # print(expressions)
    # print(data)
    print_table(expressions,data)
    if(all(b_result)):
        s= "TAUTOLOGY"
    elif(not any(b_result)):
        s= "CONTRADICTION"
    else:
        s ="CONTINGENCY"
    print(f"\nThe {expr} is {s}\n")
    return s

def tt_determine_onlytf(expr) -> str:
    expressions = []
    data = []
    variable =[]
    b_result=[]
    get_names = True
    # print(variable)
    test_dict = {}
    temp_data = list(test_dict.values())
    test_dict.update(BOOLSYNTAX)
    tree = expTOtree(test_dict,expr)
    if(get_names):
        expressions.extend(tree.get_names_list([]))
        get_names = False
    t = tree.evaluate_tuple([])
    # print(type(tree))
    if isinstance(tree,Op_Node):
        temp_data.extend(t[1])
    else:
        temp_data.extend([t[0]])
    # print(temp_data)
    b_result.append(t[0])
    data.append(temp_data)
    # print(data)
    # print(test_dict)
    # print(expressions)
    # print(data)
    print_table(expressions,data)
    if(all(b_result)):
        s= "TAUTOLOGY"
    elif(not any(b_result)):
        s= "CONTRADICTION"
    else:
        s ="CONTINGENCY"
    print(f"\nThe {expr} is {s}\n")
    return s

def part1_example():
    assignment = {}
    assignment.update(BOOLSYNTAX)
    Expr = "(( P1 & p2 ) | ( p3 & T )) | ((! P1 & ! P3 ) & P2 )" 
    Expr2 ="  P1 |  p2  (( )&( ))"
    Expr3 =" p1 "
    try:
        print(f"The example expression used here is:\n {Expr}")
        tree = expTOtree(assignment,Expr)
    except InvalidExpression as err:
        print("\nInvalid expression detected!\n")
        print(f"Session terminated due to invalid expression in {err.message} .\n")
        return
    except InvalidTruth as err:
        print("\nInvalid truth assignment detected!\n")
        print(f"Session terminated due to invalid truth assignment in {err.message} .\n")
        return

    print(tree.getstr())
    printtree(tree)
    print(f"The truth assignment used was: {dict(assignment.items() - BOOLSYNTAX.items())}.")
    print(f"The evaluation of the expression is {tree.evaluate()}")

    # t = tree.evaluate_tuple([])
    # print(t[0])
    # print(t[1])
    # print(tree.get_names_list([]))

def part2_example():
    Expr1 = "(! P1 & ( P1 | P2 ) > P2 )"
    Expr2 = "P2 & ( P1 > ! P2 ) & ( ! P1 > ! P2 )"
    Expr3 = "( P1 > ( P2 > P3 )) > (( P1 > P2 ) > P3 )"
    # Expr = "(( P1 & p2 ) | ( p3 & T )) | ((! P1 & ! P3 ) & P2 )"  
    print("\nRunnning the examples in part 2...\n")
    for count,expr in enumerate([Expr1,Expr2,Expr3]):
        try:
            test = tt_determine(expr,get_var_token(expr.split()))
            if count < 2:
                e = input("Press Enter to Continue...")
        except InvalidExpression as err:
            print("\nInvalid expression detected!\n")
            print(f"Truth table could not be generated due to invalid expression {err.message} in {expr}.\n")
        except InvalidTruth as err:
            print("\nInvalid truth assignment detected!\n")
            print(f"Unknown truth value in {err.message} . This error is only left for placeholder and should not be triggered. \n")
        except EmptyTruth as err:
            print("The expression provided is a constant value.")
            test = tt_determine_onlytf(expr)

# part1_example()
# part2_example()

def helpmessage():
    print("Usage:")
    print("Parsetree.py (-h | --help) ")
    print("Parsetree.py (-e | --example)")
    print("Parsetree.py  -i")
    print("Parsetree.py  -f P_file [S_file]")
    print("Parsetree.py  -n1 string_P string_S")
    print("Parsetree.py  -n2 string_P")
    print("\n\n")
    print("Options:")
    print("-h --help \t Show this help message ")
    print("-e --example \t Show examples in the manual ")
    print("-i \t \t Take P and S from guided user input")
    print("-f \t \t Take P ans S from txt files")
    print("-n1 \t \t Take P and S from command lines")
    print("-n2 \t \t Take P to generate truth table")
    sys.exit(1)

def option_input():
    print("Please follow the guide carefully.\n")
    print("Please enter the proposition string you wish to process. Each variable and operator has to be separated by the white space.")
    s_input = input(">>")
    try:
        print("The proposition entered is:" + s_input)
        token = tokenize(s_input)
    except InvalidExpression as err:
        print("\nInvalid expression detected!\n")
        print(f"Session terminated due to invalid expression in {err.message} .\n")
        return
    print("Would you like to \n1-generate a truth table or 2-keep entering truth assignment?")
    print("Enter 1 or 2")
    tt = False
    while(True):
        s_int = input(">>")
        try:
            option = int(s_int)
        except ValueError:
            print("Please enter 1 or 2. Or 0 to abort.")
            continue
        if option == 1:
            tt = True
            break
        elif option == 2:
            break
        elif option == 0:
            print("Session aborted")
            return
        else:
            print("Please enter 1 or 2. Or 0 to abort.")
    if tt:
        try:
            truth_table = tt_determine(s_input,get_var_token(s_input.split()))
        except EmptyTruth:
            print("No truth assignment is needed since the expression is a constant value.")
            truth_talbe = tt_determine_onlytf(s_input)
        return 
    else:
        a_dict ={}
        a_dict.update(BOOLSYNTAX)
        t = expTOtree(a_dict,s_input)
        print(t.getstr())
        printtree(t)
        print(f"The truth assignment used was: {dict(a_dict.items() - BOOLSYNTAX.items())}.")
        print(f"The evaluation of the expression is {t.evaluate()}")
        return

def generalized_v2(expr_l):
    for count,expr in enumerate(expr_l):
        try:
            test = tt_determine(expr,get_var_token(expr.split()))
        except InvalidExpression as err:
            print("\nInvalid expression detected!\n")
            print(f"Truth table could not be generated due to invalid expression {err.message} in {expr}.\n")
        except InvalidTruth as err:
            print("\nInvalid truth assignment detected!\n")
            print(f"Unknown truth value in {err.message} . This error is only left for placeholder and should not be triggered. \n")
        except EmptyTruth as err:
            print("The expression provided is a constant value.")
            test = tt_determine_onlytf(expr)
        finally:
            if count < len(expr_l)-1:
                e = input("Press Enter for next expression...\n")

def generalized_v1(assg_l,expr_l):
    if len(assg_l) < len (expr_l):
        for i in range(len (expr_l) - len(assg_l)):
            assg_l.append({})

    for count,(expr,assg) in enumerate(zip(expr_l,assg_l)):
        try:
            print(f"The expression is:\n {expr}")
            print(f"The assignment is:\n {assg}")
            if isValidtruth_asg(assg) is None:
                assg.update(BOOLSYNTAX)  #EmptyTruth
            tree = expTOtree(assg,expr)
            print(tree.getstr())
            printtree(tree)
            print(f"The truth assignment used was: {dict(assg.items() - BOOLSYNTAX.items())}.")
            print(f"The evaluation of the expression is {tree.evaluate()}")

        except InvalidExpression as err:
            print("\nInvalid expression detected!\n")
            print(f"This expression could not be evaluated due to invalid expression in {err.message} .\n")

        except InvalidTruth as err:
            print("\nInvalid truth assignment detected!\n")
            print(f"This expression could not be evaluated due to invalid truth assignment in {err.message} .\n")
        
        finally:
            if count < len(expr_l)-1:
                e = input("Press Enter for next expression...\n")

def isTxt(filename):
    return filename.split(".")[-1] == "txt"

def fileread(filename) -> list:
    try:
        f = open(filename,'r')
        if not isTxt(filename):
            f.close()
            raise InvalidFileFormat("filename")
        exprs_l = [line.rstrip('\r\n') for line in f.readlines()]
        f.close()
    except FileNotFoundError:
        print (f"{filename} not found. Please specify an existing txt file.")
        return
    except InvalidFileFormat:
        print (f"{filename} is not a txt file. Please specify a txt file.")
        return
    if not exprs_l:
        print(f"{filename} is empty. No expression to read from.")
        return
    return exprs_l

def file_v1(filename_p, filename_s):
    expr_l = fileread(filename_s)
    if expr_l:
        assg_l = fileread(filename_p)
        if assg_l:
            # print(assg_l)
            assg_d_l = [dict(x) for x in [[equations.split("=",maxsplit =1) for equations in assg_s.split()] for assg_s in assg_l ]]
            # print(assg_d_l)
            for d in assg_d_l:
                for k,v in d.items():
                    # print(v)
                    # print(BOOLSYNTAX.get(v.upper()))
                    if BOOLSYNTAX.get(v.upper()) is not None:
                        d[k] = BOOLSYNTAX.get(v.upper())
            generalized_v1(assg_d_l,expr_l)

def stdin_v1(argument_p,argument_s):
    p_d = dict([equation.split('=',maxsplit =1) for equation in argument_p.split()])
    # print(p_d)
    for k,v in p_d.items():
        # print(k+v)
        if BOOLSYNTAX.get(v.upper()) is not None:
            p_d[k] = BOOLSYNTAX.get(v.upper())
    generalized_v1([p_d],[argument_s])
  

def file_v2(filename):
    exprs_l = fileread(filename)
    if exprs_l:
        generalized_v2(exprs_l)

def stdin_v2(argument):
    generalized_v2([argument])
    



def main():
    if len(sys.argv) == 1:
        sys.argv[1:] = ['-h']
    
    args = sys.argv[1:]

    # print(args)
    
    if len(args) == 1:
        if args[0] in ('-h','--help'):
            helpmessage()
        elif args[0] in('-e','--example'):
            print("> Starting Part 1 example\n")
            part1_example()
            e = input("Part 1 example done. Press Enter to Continue...")
            part2_example()
            print("Part 2 example done.")
        elif args[0] == '-i':
            option_input()
            print("\nSession completed.\n")
        else:
            print("Invalid command/argument combination. Use Parsetree.py -h for details. ")
    elif len(args) == 2 :
        if args[0] in ('-f'):
            file_v2(args[1])
            print("\nSession completed.\n")
        elif args[0] in ('-n2'):
            stdin_v2(args[1])
            print("\nSession completed.\n")
        else:
            print("Invalid command/argument combination. Use Parsetree.py -h for details. ")
    elif len(args ) == 3:
        if args[0] in ('-f'):
            file_v1(args[1],args[2])
            print("\nSession completed.\n")
        elif args[0] in ("-n1"):
            stdin_v1(args[1],args[2])
            print("\nSession completed.\n")
        else:
            print("Invalid command/argument combination. Use Parsetree.py -h for details. ")
    else:
        print("Invalid command/argument combination. Use Parsetree.py -h for details. ")

if __name__ == '__main__':
    main()